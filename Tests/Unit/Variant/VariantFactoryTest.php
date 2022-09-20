<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Variant;

use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VariantFactoryTest extends UnitTestCase
{
    /**
     * @var bool
     */
    protected $resetSingletonInstances = true;

    private VariantFactory $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $typoScript = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'variants.' => [
                            'desktop.' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $configurationManagerMock = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManagerMock->method('getConfiguration')->willReturn($typoScript);

        $this->subject = new VariantFactory($configurationManagerMock);
    }

    /**
     * @test
     */
    public function createNewInstanceAndReceiveGivenConfiguration(): void
    {
        $variant = $this->subject->get('desktop');

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame(['foo' => 'bar'], $variant->getConfig());
    }

    /**
     * @test
     */
    public function createNewInstanceAndThrowNoSuchVariantException(): void
    {
        $this->expectException(NoSuchVariantException::class);
        $this->expectExceptionCode(1623538021);

        $this->subject->get('mobile');
    }

    /**
     * @test
     */
    public function hasVariantReturnsTrue(): void
    {
        self::assertTrue($this->subject->has('desktop'));
    }

    /**
     * @test
     */
    public function hasVariantReturnsFalse(): void
    {
        self::assertFalse($this->subject->has('mobile'));
    }

    /**
     * @test
     */
    public function getVariantKeyUseDefault(): void
    {
        $typoScript = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'variants.' => [
                            'default.' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $configurationManagerMock = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManagerMock->method('getConfiguration')->willReturn($typoScript);
        $subject = new VariantFactory($configurationManagerMock);
        $variant = $subject->get();

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame(['foo' => 'bar'], $variant->getConfig());
    }

    /**
     * @test
     */
    public function getVariantKeyUseFromRegistry(): void
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->register['IMAGE_VARIANT_KEY'] = 'desktop';

        $variant = $this->subject->get();

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame(['foo' => 'bar'], $variant->getConfig());
    }
}
