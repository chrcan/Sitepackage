<?php

declare(strict_types=1);

/*
 * (c) 2024 rc design visual concepts (rc-design.at)
 * _________________________________________________
 * The TYPO3 project - inspiring people to share!
 * _________________________________________________
 */

defined('TYPO3') or die();

call_user_func(
    function (): void {
        $extensionKey = 'rcdesign';

        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $extensionKey,
            'Configuration/TypoScript/',
            'rcdesign Sitepackage'
        );
    }
);
