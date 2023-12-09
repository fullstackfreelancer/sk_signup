<?php
declare(strict_types=1);

namespace SIMONKOEHLER\SkSignup\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final class UserRepository extends Repository
{

    private $uriBuilder = NULL;

    public function initializeObject()
    {
        /** @var QuerySettingsInterface $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        // Show users from all pages
        $querySettings->setRespectStoragePage(false);
        $querySettings->setEnableFieldsToBeIgnored(['disable']);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function passwordIsValid($passwordString)
    {
        $minLength = 8;

        // Check minimum length
        if (strlen($passwordString) < $minLength) {
            return false;
        }

        // Check for at least 1 number
        if (!preg_match('/[0-9]/', $passwordString)) {
            return false;
        }

        // Check for at least 1 special character
        if (!preg_match('/[^a-zA-Z0-9]/', $passwordString)) {
            return false;
        }

        // Check for at least 1 capital letter
        if (!preg_match('/[A-Z]/', $passwordString)) {
            return false;
        }

        // All validation checks passed
        return true;
    }

    public function inRange($string, $minLength, $maxLength)
    {
        $trimmedString = trim($string);
        $length = strlen($trimmedString);
        return ($length >= $minLength && $length <= $maxLength);
    }

    public function sendConfirmation($settings,$user,$uniqueId)
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $url = $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->setLanguage('current')
            ->setTargetPageUid((int)$settings['userpages']['signup'])->build();
        $link = $url.'?tx_sksignup_default%5Baction%5D=confirm&tx_sksignup_default%5Bkey%5D='.$uniqueId;
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new Address($settings['email']['senderEmail'], $settings['email']['senderName']));
        $mail->to(
            new Address($user->getEmail())
        );
        $mail->subject('Hi '.$user->getUsername().'! Please confirm your email address.');
        $mail->text('Dear '.$user->getUsername().",\n\n"."please confirm your identity by clicking the following link:\n\n".$link);
        $mail->send();
    }

    public function userWithKeyExists($key)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users')->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $query = $queryBuilder
            ->select('uid','pid','tx_sksignup_key','username')
            ->from('fe_users')
            ->where($queryBuilder->expr()->eq('tx_sksignup_key', $queryBuilder->createNamedParameter($key)));
        $rows = $query->execute()->fetchAll();
        return $rows;
    }

    public function getRawUserData($uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users')->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $query = $queryBuilder
            ->select('uid','username','company','first_name','middle_name','last_name','email','telephone','address','zip','city','country')
            ->from('fe_users')
            ->setMaxResults(1)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
            );
        //$rows = $query->execute()->fetchAll();
        return $query->execute()->fetchAll()[0];
    }

    public function activateUser($key)
    {
        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users')->update(
            'fe_users',
            [ 'disable' => '0','tx_sksignup_key' => '','description' => 'Activated: '.(new \DateTime())->format('Y-m-d h:i:s') ], // set
            [ 'tx_sksignup_key' => $key ]  // where
        );
    }

    public function saveSettings($args)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users')->createQueryBuilder();
        $queryBuilder
            ->update('fe_users')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($args['uid'], Connection::PARAM_INT))
            )
            ->set('company', $args['company'])
            ->set('telephone', $args['telephone'])
            ->set('first_name', $args['first_name'])
            ->set('middle_name', $args['middle_name'])
            ->set('last_name', $args['last_name'])
            ->set('address', $args['address'])
            ->set('zip', $args['zip'])
            ->set('city', $args['city'])
            ->set('country', $args['country']);

        if(strlen(trim($args['password'])) > 0){
            $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
            $hashedPassword = $hashInstance->getHashedPassword(trim($args['password']));
            $queryBuilder->set('password', $hashedPassword);
        }

        return $queryBuilder->executeStatement();
    }

}
