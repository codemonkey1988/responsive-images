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
