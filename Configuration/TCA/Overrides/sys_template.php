<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript',
    'Responsive images settings'
);

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript/DefaultConfiguration',
    'Deprecated: Responsive images default configuration (optional)'
);

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript/BootstrapConfiguration',
    'Responsive images bootstrap configuration (optional)'
);
