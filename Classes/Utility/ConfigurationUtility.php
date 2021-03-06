<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Utility;

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

/**
 * Utility class for this extension.
 */
class ConfigurationUtility
{
    const DEFAULT_SUPPORTED_MIME_TYPES = 'image/jpeg,image/jpg,image/gif,image/png';
    const DEFAULT_DESKTOP_WIDTH = 1920;
    const DEFAULT_TABLET_WIDTH = 1024;
    const DEFAULT_SMARTPHONE_WIDTH = 320;

    /**
     * Returns extension management configuration as array.
     *
     * @return array
     */
    public static function getExtensionConfig(): array
    {
        $supportedMimeTypes = self::DEFAULT_SUPPORTED_MIME_TYPES;
        $desktopWidth = self::DEFAULT_DESKTOP_WIDTH;
        $tabletWidth = self::DEFAULT_TABLET_WIDTH;
        $smartphoneWidth = self::DEFAULT_SMARTPHONE_WIDTH;

        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images'])) {
            $supportedMimeTypes = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images']['supportedMimeTypes'] ?? self::DEFAULT_SUPPORTED_MIME_TYPES;
            $desktopWidth = (int)$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images']['maxDesktopImageWidth'] ?? self::DEFAULT_DESKTOP_WIDTH;
            $tabletWidth = (int)$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images']['maxTabletImageWidth'] ?? self::DEFAULT_TABLET_WIDTH;
            $smartphoneWidth = (int)$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images']['maxSmartphoneImageWidth'] ?? self::DEFAULT_SMARTPHONE_WIDTH;
        } elseif (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images'])) {
            try {
                $extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images']);

                if (!empty($extConfig['supportedMimeTypes'])) {
                    $supportedMimeTypes = $extConfig['supportedMimeTypes'];
                }

                if (isset($extConfig['maxDesktopImageWidth']) && is_numeric($extConfig['maxDesktopImageWidth'])) {
                    $desktopWidth = (int)$extConfig['maxDesktopImageWidth'];
                }

                if (isset($extConfig['maxTabletImageWidth']) && is_numeric($extConfig['maxTabletImageWidth'])) {
                    $tabletWidth = (int)$extConfig['maxTabletImageWidth'];
                }

                if (isset($extConfig['maxSmartphoneImageWidth']) && is_numeric($extConfig['maxSmartphoneImageWidth'])) {
                    $smartphoneWidth = (int)$extConfig['maxSmartphoneImageWidth'];
                }
            } catch (\Exception $e) {
            }
        }

        return [
            'supportedMimeTypes' => $supportedMimeTypes,
            'maxDesktopImageWidth' => $desktopWidth,
            'maxTabletImageWidth' => $tabletWidth,
            'maxSmartphoneImageWidth' => $smartphoneWidth,
        ];
    }

    /**
     * @return bool
     */
    public static function isEnabled(): bool
    {
        $enabled = true;
        $enabledByEnv = getenv('RESPONSIVE_IMAGES_ENABLED');

        if ($enabledByEnv !== false) {
            $enabled = (bool)$enabledByEnv;
        } elseif (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.'])) {
            $enabled = (bool)$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['enabled'];
        }

        return $enabled;
    }

    /**
     * @return bool
     */
    public static function isProcessingEnabled(): bool
    {
        $processing = true;
        $processingByEnv = getenv('RESPONSIVE_IMAGES_PROCESSING');

        if ($processingByEnv !== false) {
            $processing = (bool)$processingByEnv;
        } elseif (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.'])) {
            $processing = (bool)$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['processing'];
        }

        return $processing;
    }
}
