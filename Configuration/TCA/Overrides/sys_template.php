<?php

/*
 * This file is part of the TYPO3 responsive images project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 *
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
    'Responsive images default configuration (optional)'
);

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript/LazySizesConfiguration',
    'Responsive images lazySizes configuration (optional)'
);
