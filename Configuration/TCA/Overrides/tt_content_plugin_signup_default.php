<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:sk_signup/Resources/Private/Language/locallang_db.xlf:plugin.default.title',
        'signup_default',
        'tx_signup-default',
    ],
    'list_type',
    'examples'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['signup_default'] = 'pages,layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['signup_default'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'signup_default',
    'FILE:EXT:sk_signup/Configuration/Flexforms/SignupDefault.xml'
);
