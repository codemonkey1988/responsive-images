<?php
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
    /**
     * Returns extension management configuration as array.
     *
     * @return array
     */
    public static function getExtensionConfig()
    {
        $desktopWidth = 1920;
        $tabletWidth = 1024;
        $smartphoneWidth = 320;

        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images'])) {
            try {
                $extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images']);

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
            'maxDesktopImageWidth' => $desktopWidth,
            'maxTabletImageWidth' => $tabletWidth,
            'maxSmartphoneImageWidth' => $smartphoneWidth,
        ];
    }
}
