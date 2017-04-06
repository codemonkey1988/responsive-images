<?php
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

if (!defined('TYPO3_MODE')) {
    die('Access denied');
}

/** @var \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry $rendererRegistry */
$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureTagRenderer::class);

// Add default configs
if (!function_exists('addImageVariants')) {
    function addImageVariants()
    {
        $extConfig       = \Codemonkey1988\ResponsiveImages\Utility\GeneralUtility::getExtensionConfig();
        $desktopWidth    = $extConfig['maxDesktopImageWidth'];
        $tabletWidth     = $extConfig['maxTabletImageWidth'];
        $smartphoneWidth = $extConfig['maxSmartphoneImageWidth'];

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $default */
        $default = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'default');
        $default->setDefaultWidth($desktopWidth)
                ->addSourceConfig('(max-width: 40em)',
                    array(
                        '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                        '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                    ))
                ->addSourceConfig('(min-width: 64.0625em)',
                    array(
                        '1x' => array('width' => $desktopWidth),
                        '2x' => array('width' => $desktopWidth * 2, 'quality' => 80)
                    ))
                ->addSourceConfig('(min-width: 40.0625em)',
                    array(
                        '1x' => array('width' => $tabletWidth, 'quality' => 80),
                        '2x' => array('width' => $tabletWidth * 2, 'quality' => 60)
                    ));

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $half */
        $half = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'half');
        $half->setDefaultWidth($desktopWidth / 2)
             ->addSourceConfig('(max-width: 40em)',
                 array(
                     '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                     '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                 ))
             ->addSourceConfig('(min-width: 64.0625em)',
                 array(
                     '1x' => array('width' => $desktopWidth / 2),
                     '2x' => array('width' => $desktopWidth * 2 / 2, 'quality' => 80)
                 ))
             ->addSourceConfig('(min-width: 40.0625em)',
                 array(
                     '1x' => array('width' => $tabletWidth / 2, 'quality' => 80),
                     '2x' => array('width' => $tabletWidth * 2 / 2, 'quality' => 60)
                 ));

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $third */
        $third = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'third');
        $third->setDefaultWidth($desktopWidth / 3)
              ->addSourceConfig('(max-width: 40em)',
                  array(
                      '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                      '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                  ))
              ->addSourceConfig('(min-width: 64.0625em)',
                  array(
                      '1x' => array('width' => $desktopWidth / 3),
                      '2x' => array('width' => $desktopWidth * 2 / 3, 'quality' => 80)
                  ))
              ->addSourceConfig('(min-width: 40.0625em)',
                  array(
                      '1x' => array('width' => $tabletWidth / 3, 'quality' => 80),
                      '2x' => array('width' => $tabletWidth * 2 / 3, 'quality' => 60)
                  ));

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $quarter */
        $quarter = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'quarter');
        $quarter->setDefaultWidth($desktopWidth / 4)
                ->addSourceConfig('(max-width: 40em)',
                    array(
                        '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                        '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                    ))
                ->addSourceConfig('(min-width: 64.0625em)',
                    array(
                        '1x' => array('width' => $desktopWidth / 4),
                        '2x' => array('width' => $desktopWidth * 2 / 4, 'quality' => 80)
                    ))
                ->addSourceConfig('(min-width: 40.0625em)',
                    array(
                        '1x' => array('width' => $tabletWidth / 4, 'quality' => 80),
                        '2x' => array('width' => $tabletWidth * 2 / 4, 'quality' => 60)
                    ));

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $two_thirds */
        $two_thirds = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'two-thirds');
        $two_thirds->setDefaultWidth($desktopWidth / 0.66666)
                   ->addSourceConfig('(max-width: 40em)',
                       array(
                           '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                           '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                       ))
                   ->addSourceConfig('(min-width: 64.0625em)',
                       array(
                           '1x' => array('width' => $desktopWidth / 0.66666),
                           '2x' => array('width' => $desktopWidth * 2 / 0.66666, 'quality' => 80)
                       ))
                   ->addSourceConfig('(min-width: 40.0625em)',
                       array(
                           '1x' => array('width' => $tabletWidth / 0.66666, 'quality' => 80),
                           '2x' => array('width' => $tabletWidth * 2 / 0.66666, 'quality' => 60)
                       ));

        /** @var \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant $three_quarters */
        $three_quarters = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureImageVariant::class,
            'three-quarters');
        $three_quarters->setDefaultWidth($desktopWidth / 0.75)
                       ->addSourceConfig('(max-width: 40em)',
                           array(
                               '1x' => array('width' => $smartphoneWidth, 'quality' => 65),
                               '2x' => array('width' => $smartphoneWidth * 2, 'quality' => 40)
                           ))
                       ->addSourceConfig('(min-width: 64.0625em)',
                           array(
                               '1x' => array('width' => $desktopWidth / 0.75),
                               '2x' => array('width' => $desktopWidth * 2 / 0.75, 'quality' => 80)
                           ))
                       ->addSourceConfig('(min-width: 40.0625em)',
                           array(
                               '1x' => array('width' => $tabletWidth / 0.75, 'quality' => 80),
                               '2x' => array('width' => $tabletWidth * 2 / 0.75, 'quality' => 60)
                           ));

        $registry = \Codemonkey1988\ResponsiveImages\Resource\Rendering\PictureVariantsRegistry::getInstance();
        $registry->registerImageVariant($default);
        $registry->registerImageVariant($half);
        $registry->registerImageVariant($third);
        $registry->registerImageVariant($quarter);
        $registry->registerImageVariant($two_thirds);
        $registry->registerImageVariant($three_quarters);
    }
}

addImageVariants();