<?php

declare(strict_types=1);

/*
 * (c) 2024 rc design visual concepts (rc-design.at)
 * _________________________________________________
 * The TYPO3 project - inspiring people to share!
 * _________________________________________________
 */

if (!defined('TYPO3')) {
    die('Access denied.');
}
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile('rcdesign', 'Configuration/TSconfig/BackendLayouts.tsconfig', 'Default Backendlayouts');
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile('rcdesign', 'Configuration/TSconfig/Page.tsconfig', 'Default PageTSconfig');
