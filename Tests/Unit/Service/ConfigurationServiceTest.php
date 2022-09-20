<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Utility;

use Codemonkey1988\ResponsiveImages\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility
 */
class ConfigurationServiceTest extends UnitTestCase
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
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn([]);
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class, $configurationManager);

        self::assertTrue($configurationService->isEnabled());
    }

    /**
     * @test
     */
    public function processingEnabledByDefault()
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn([]);
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class, $configurationManager);

        self::assertTrue($configurationService->isProcessingEnabled());
    }

    /**
     * @test
     */
    public function extensionDisabledByTypoScript()
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn(['enabled' => '0']);
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class, $configurationManager);

        self::assertFalse($configurationService->isEnabled());
    }

    /**
     * @test
     */
    public function processingDisabledByTypoScript()
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn(['processing' => '0']);
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class, $configurationManager);

        self::assertFalse($configurationService->isProcessingEnabled());
    }
}
