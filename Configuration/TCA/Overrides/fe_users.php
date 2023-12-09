<?php
defined('TYPO3') or die();

// Add some fields to FE Users table to show TCA fields definitions
// USAGE: TCA Reference > $TCA array reference > ['columns'][fieldname]['config'] / TYPE: "select"
$temporaryColumns = array(
    'tx_signup_key' => array(
        'exclude' => 0,
        'label' => 'Signup KEY',
        'config' => array(
            'type' => 'input',
            'size' => 30,
            'max' => 255,
        )
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'fe_users', $temporaryColumns, 1
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users', 'tx_signup_key'
);
