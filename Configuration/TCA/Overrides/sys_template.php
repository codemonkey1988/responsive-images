<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript/DefaultConfiguration',
    'Responsive images default configuration (optional)'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'responsive_images',
    'Configuration/TypoScript/BootstrapConfiguration',
    'Responsive images bootstrap configuration (optional)'
);
