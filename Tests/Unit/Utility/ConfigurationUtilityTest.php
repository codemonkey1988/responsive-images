<?php
namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Utility;

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

use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility
 */
class ConfigurationUtilityTest extends UnitTestCase
{
    /**
     * Test is the default extension config is loaded correctly.
     *
     * @test
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return void
     */
    public function defaultExtensionConfig()
    {
        $extensionConfig = ConfigurationUtility::getExtensionConfig();
        $expectedConfig = [
            'maxDesktopImageWidth' => 1920,
            'maxTabletImageWidth' => 1024,
            'maxSmartphoneImageWidth' => 320,
        ];

        $this->assertTrue(is_array($extensionConfig));
        $this->assertEquals($expectedConfig, $extensionConfig);
    }

    /**
     * Test if user changes to the extension config are loaded correctly.
     *
     * @test
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return void
     */
    public function customExtensionConfig()
    {
        // Setup the data.
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images'] = serialize(
            [
                'maxDesktopImageWidth' => '2560',
                'maxTabletImageWidth' => '1280',
                'maxSmartphoneImageWidth' => '360',
            ]
        );

        $extensionConfig = ConfigurationUtility::getExtensionConfig();
        $expectedConfig = [
            'maxDesktopImageWidth' => 2560,
            'maxTabletImageWidth' => 1280,
            'maxSmartphoneImageWidth' => 360,
        ];

        $this->assertTrue(is_array($extensionConfig));
        $this->assertEquals($expectedConfig, $extensionConfig);
    }
}
