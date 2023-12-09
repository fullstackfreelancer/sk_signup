<?php
defined('TYPO3') or die();

(static function (): void {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SkSignup',
        'Default',
        'Signup Plugin'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SkSignup',
        'Dashboard',
        'Signup User Dashboard'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'SkSignup',
        'Settings',
        'Signup Settings Form'
    );

})();
