<?php
namespace SIMONKOEHLER\Signup\Controller;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use SIMONKOEHLER\Signup\Domain\Repository\UserRepository;

final class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    private ?UserRepository $userRepository = null;

    public function injectUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function dashboardAction(): ResponseInterface
    {
        $context = GeneralUtility::makeInstance(Context::class);

        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $userId = $context->getPropertyFromAspect('frontend.user', 'id');
            $user = $this->userRepository->getRawUserData($userId);
            $this->view->assignMultiple([
                'user' => $user
            ]);
        }

        return $this->htmlResponse();
    }

    public function settingsAction(): ResponseInterface
    {
        $context = GeneralUtility::makeInstance(Context::class);

        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $userId = $context->getPropertyFromAspect('frontend.user', 'id');
            $user = $this->userRepository->getRawUserData($userId);

            $this->view->assignMultiple([
                'user' => $user
            ]);
        }

        if($this->request->hasArgument('save_settings')){
            if($this->userRepository->saveSettings($this->request->getArguments()) > 0){
                $this->addFlashMessage(
                   'Your settings have been saved.',
                   'Success',
                   ContextualFeedbackSeverity::OK,
                   true
                );
                return $this->redirect('settings',NULL,NULL,[
                    'saved' => 1
                ]);
            }
            else{
                $this->addFlashMessage(
                   'Nothing has been changed.',
                   'Info',
                   ContextualFeedbackSeverity::OK,
                   false
                );
                return $this->htmlResponse();
            }
        }
        else{
            return $this->htmlResponse();
        }

    }

}
