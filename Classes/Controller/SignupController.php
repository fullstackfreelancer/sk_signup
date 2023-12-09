<?php
namespace SIMONKOEHLER\SkSignup\Controller;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use SIMONKOEHLER\Signup\Domain\Repository\UserRepository;

final class SignupController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    private ?UserRepository $userRepository = null;
    public function injectUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function formAction(): ResponseInterface
    {
        if($this->request->hasArgument('init')){

            $args = $this->request->getArguments();
            $user = new \SIMONKOEHLER\Signup\Domain\Model\User();
            $errors = [];
            $check_username_existance = true;
            $check_email_existance = true;

            // Check if username is empty
            if(trim($args['username']) === ''){
                $errors[] = 'username_empty';
            }
            if(!$this->userRepository->inRange($args['username'],3,20)){
                $errors[] = 'username_outofrange';
            }
            // If not empty, check if username already exists in database
            if(!isset($errors['username_empty']) && !isset($errors['username_outofrange'])){
                if($this->userRepository->count(['username' => trim($args['username'])])){
                    $errors[] = 'username_exists';
                }
            }
            // Check if email is empty
            if (trim($args['email']) === '') {
                $errors[] = 'email_empty';
                $check_email_existance = false;
            }
            // Check if email is valid email address
            if (!filter_var(trim($args['email']), FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'email_invalid';
                $check_email_existance = false;
            }
            // Check if email already exists in database
            if($check_email_existance === true){
                if ($this->userRepository->count(['email' => trim($args['email'])])) {
                    $errors[] = 'email_exists';
                }
            }
            // Check if password is empty
            if (trim($args['password']) === '') {
                $errors[] = 'password_empty';
            }
            // Check if password is valid and safe enough
            if (!$this->userRepository->passwordIsValid(trim($args['password']))) {
                $errors[] = 'password_invalid';
            }

            if(count($errors) === 0){

                // Generate hashed password from given plain text password
                $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
                $hashedPassword = $hashInstance->getHashedPassword(trim($args['password']));

                // Unique ID for confirmation
                $uniqueId = uniqid('', true);

                // Prepare user and add to database
                $user->setUsername($args['username']);
                $user->setEmail($args['email']);
                $user->setPassword($hashedPassword);
                $user->setUsergroup($this->settings['defaultUserGroup']);
                $user->setTxExtbaseType('0');
                $user->setPid($this->settings['storagePid']);
                $user->setTxSignupKey($uniqueId);
                $user->setDisable('1');
                $this->userRepository->add($user);

                // Prepare Extbase view
                $this->view->assign('message', 'User added!');
                $this->view->assign('status', 'user_added');
                $this->view->assignMultiple([
                    'username' => $args['username'],
                    'email' => $args['email']
                ]);

                // Finally, send confirmation email to user
                $this->userRepository->sendConfirmation($this->settings,$user,$uniqueId);
            }
            else{
                $this->view->assign('message', 'The form has errors:');
                $this->view->assign('errors', $errors);
                $this->view->assign('status', 'errors');
            }

            $this->view->assign('args', $args);

        }

        return $this->htmlResponse();
    }

    public function confirmAction(): ResponseInterface
    {
        if($this->request->hasArgument('key') and trim($this->request->getArgument('key')) !== ''){
            $args = $this->request->getArguments();
            $user = $this->userRepository->userWithKeyExists($args['key']);
            if(!empty($user)){
                $this->userRepository->activateUser($args['key']);
                $this->view->assign('status','success');
            }
            else{
                $this->view->assign('status','failure');
            }
        }
        else{
            $this->view->assign('status','nokey');
        }

        return $this->htmlResponse();
    }
}
