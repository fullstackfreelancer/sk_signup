<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:sk_signup/Resources/Private/Language/locallang_db.xlf:plugin.default.title',
        'sksignup_default',
        'tx_sksignup-default',
    ],
    'list_type',
    'examples'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sksignup_default'] = 'pages,layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['sksignup_default'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'sksignup_default',
    'FILE:EXT:sk_signup/Configuration/Flexforms/SignupDefault.xml'
);
