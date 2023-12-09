<?php
declare(strict_types=1);

defined('TYPO3') or die();

use SIMONKOEHLER\Signup\Controller\UserController;
use SIMONKOEHLER\Signup\Controller\SignupController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Configure Plugins
ExtensionUtility::configurePlugin(
    'SkSignup',
    'Default',
    [SignupController::class => 'form,confirm'],
    [SignupController::class => 'form,confirm']
);

ExtensionUtility::configurePlugin(
    'SkSignup',
    'Dashboard',
    [UserController::class => 'dashboard'],
    [UserController::class => 'dashboard']
);

ExtensionUtility::configurePlugin(
    'SkSignup',
    'Settings',
    [UserController::class => 'settings'],
    [UserController::class => 'settings']
);

// Add default TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
    "@import 'EXT:sk_signup/Configuration/TypoScript/constants.typoscript'"
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    "@import 'EXT:sk_signup/Configuration/TypoScript/setup.typoscript'"
);

// Make sure no cHash is needed when signup is called from emails etc..
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_signup_default[action]';
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_signup_default[key]';
