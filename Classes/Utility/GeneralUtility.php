<?php
namespace Codemonkey1988\ResponsiveImages\Utility;

/***************************************************************
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class GeneralUtility
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Utility
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class GeneralUtility
{
    /**
     * Returns extension management configuration as array.
     *
     * @return array
     */
    static public function getExtensionConfig()
    {
        $desktopWidth    = 1920;
        $tabletWidth     = 1024;
        $smartphoneWidth = 320;

        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images'])) {
            try {
                $extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images']);

                if (isset($extConfig['maxDesktopImageWidth']) && is_numeric($extConfig['maxDesktopImageWidth'])) {
                    $desktopWidth = (int)$extConfig['maxDesktopImageWidth'];
                }

                if (isset($extConfig['maxTabletImageWidth']) && is_numeric($extConfig['maxTabletImageWidth'])) {
                    $smartphoneWidth = (int)$extConfig['maxTabletImageWidth'];
                }

                if (isset($extConfig['maxSmartphoneImageWidth']) && is_numeric($extConfig['maxSmartphoneImageWidth'])) {
                    $smartphoneWidth = (int)$extConfig['maxSmartphoneImageWidth'];
                }
            } catch (\Exception $e) {
            }
        }

        return array(
            'maxDesktopImageWidth'    => $desktopWidth,
            'maxTabletImageWidth'     => $tabletWidth,
            'maxSmartphoneImageWidth' => $smartphoneWidth,
        );
    }
}