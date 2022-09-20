<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Utility;

use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility
 */
class ConfigurationUtilityTest extends UnitTestCase
{
    /**
     * @var bool
     */
    protected $resetSingletonInstances = true;

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

        self::assertTrue(is_array($extensionConfig));
        self::assertEquals($expectedConfig, $extensionConfig);
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

        self::assertTrue(is_array($extensionConfig));
        self::assertEquals($expectedConfig, $extensionConfig);
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

        self::assertTrue(is_array($extensionConfig));
        self::assertEquals($expectedConfig, $extensionConfig);
    }

    /**
     * @test
     */
    public function extensionEnabledByDefault()
    {
        self::assertTrue(ConfigurationUtility::isEnabled());
    }

    /**
     * @test
     */
    public function processingEnabledByDefault()
    {
        self::assertTrue(ConfigurationUtility::isProcessingEnabled());
    }

    /**
     * @test
     */
    public function extensionDisabledByEnv()
    {
        putenv('RESPONSIVE_IMAGES_ENABLED=0');
        $isEnabled = ConfigurationUtility::isEnabled();
        putenv('RESPONSIVE_IMAGES_ENABLED');

        self::assertFalse($isEnabled);
    }

    /**
     * @test
     */
    public function extensionDisabledByTypoScript()
    {
        $tsfeMock = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $packageManagerMock = $this->getMockBuilder(PackageManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tsfeMock->tmpl = GeneralUtility::makeInstance(TemplateService::class, null, $packageManagerMock);
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

        self::assertFalse($isEnabled);
    }

    /**
     * @test
     */
    public function processingDisabledByEnv()
    {
        putenv('RESPONSIVE_IMAGES_PROCESSING=0');
        $isProcessingEnabled = ConfigurationUtility::isProcessingEnabled();
        putenv('RESPONSIVE_IMAGES_PROCESSING');

        self::assertFalse($isProcessingEnabled);
    }

    /**
     * @test
     */
    public function processingDisabledByTypoScript()
    {
        $tsfeMock = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $packageManagerMock = $this->getMockBuilder(PackageManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tsfeMock->tmpl = GeneralUtility::makeInstance(TemplateService::class, null, $packageManagerMock);
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

        self::assertFalse($isProcessingEnabled);
    }
}
