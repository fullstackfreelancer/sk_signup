<?php
defined('TYPO3') || die();

call_user_func(function()
{

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'sk_signup',
        'Configuration/TypoScript',
        'SignUp: Basic Setup for Frontend Registration'
    );

});
