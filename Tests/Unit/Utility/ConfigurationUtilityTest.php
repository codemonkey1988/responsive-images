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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility
 */
class ConfigurationUtilityTest extends UnitTestCase
{
    /**
     * Test is the default extension config is loaded correctly.
     *
     * @test
     */
    public function defaultExtensionConfig()
    {
        $extensionConfig = ConfigurationUtility::getExtensionConfig();
        $expectedConfig = [
            'supportedMimeTypes' => 'image/jpeg,image/jpg,image/gif,image/png',
            'maxDesktopImageWidth' => 1920,
            'maxTabletImageWidth' => 1024,
            'maxSmartphoneImageWidth' => 320,
        ];

        $this->assertTrue(is_array($extensionConfig));
        $this->assertEquals($expectedConfig, $extensionConfig);
    }

    /**
     * Test if user changes to the extension config are loaded correctly for TYPO3 v8
     *
     * @test
     */
    public function customExtensionConfigV8()
    {
        // Setup the data.
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['responsive_images'] = serialize(
            [
                'supportedMimeTypes' => 'image/jpeg',
                'maxDesktopImageWidth' => '2560',
                'maxTabletImageWidth' => '1280',
                'maxSmartphoneImageWidth' => '360',
            ]
        );

        $extensionConfig = ConfigurationUtility::getExtensionConfig();
        $expectedConfig = [
            'supportedMimeTypes' => 'image/jpeg',
            'maxDesktopImageWidth' => 2560,
            'maxTabletImageWidth' => 1280,
            'maxSmartphoneImageWidth' => 360,
        ];

        $this->assertTrue(is_array($extensionConfig));
        $this->assertEquals($expectedConfig, $extensionConfig);
    }

    /**
     * Test if user changes to the extension config are loaded correctly for TYPO3 v9
     *
     * @test
     */
    public function customExtensionConfigV9()
    {
        // Setup the data.
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['responsive_images'] = [
            'supportedMimeTypes' => 'image/jpeg',
            'maxDesktopImageWidth' => '2560',
            'maxTabletImageWidth' => '1280',
            'maxSmartphoneImageWidth' => '360',
        ];

        $extensionConfig = ConfigurationUtility::getExtensionConfig();
        $expectedConfig = [
            'supportedMimeTypes' => 'image/jpeg',
            'maxDesktopImageWidth' => 2560,
            'maxTabletImageWidth' => 1280,
            'maxSmartphoneImageWidth' => 360,
        ];

        $this->assertTrue(is_array($extensionConfig));
        $this->assertEquals($expectedConfig, $extensionConfig);
    }

    /**
     * @test
     */
    public function extensionEnabledByDefault()
    {
        $this->assertTrue(ConfigurationUtility::isEnabled());
    }

    /**
     * @test
     */
    public function processingEnabledByDefault()
    {
        $this->assertTrue(ConfigurationUtility::isProcessingEnabled());
    }

    /**
     * @test
     */
    public function extensionDisabledByEnv()
    {
        putenv('RESPONSIVE_IMAGES_ENABLED=0');
        $isEnabled = ConfigurationUtility::isEnabled();
        putenv('RESPONSIVE_IMAGES_ENABLED');

        $this->assertFalse($isEnabled);
    }

    /**
     * @test
     */
    public function extensionDisabledByTypoScript()
    {
        $tsfeMock = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tsfeMock->tmpl = new TemplateService();
        $tsfeMock->tmpl->setup = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'enabled' => '0',
                    ],
                ],
            ],
        ];

        $GLOBALS['TSFE'] = $tsfeMock;
        $isEnabled = ConfigurationUtility::isEnabled();
        unset($GLOBALS['TSFE']);

        $this->assertFalse($isEnabled);
    }

    /**
     * @test
     */
    public function processingDisabledByEnv()
    {
        putenv('RESPONSIVE_IMAGES_PROCESSING=0');
        $isProcessingEnabled = ConfigurationUtility::isProcessingEnabled();
        putenv('RESPONSIVE_IMAGES_PROCESSING');

        $this->assertFalse($isProcessingEnabled);
    }

    /**
     * @test
     */
    public function processingDisabledByTypoScript()
    {
        $tsfeMock = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tsfeMock->tmpl = new TemplateService();
        $tsfeMock->tmpl->setup = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'processing' => '0',
                    ],
                ],
            ],
        ];

        $GLOBALS['TSFE'] = $tsfeMock;
        $isProcessingEnabled = ConfigurationUtility::isProcessingEnabled();
        unset($GLOBALS['TSFE']);

        $this->assertFalse($isProcessingEnabled);
    }
}
