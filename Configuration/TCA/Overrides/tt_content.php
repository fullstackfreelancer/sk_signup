<?php
defined('TYPO3') or die();

(static function (): void {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Signup',
        'Default',
        'Signup Plugin'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Signup',
        'Dashboard',
        'Signup User Dashboard'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Signup',
        'Settings',
        'Signup Settings Form'
    );

})();
