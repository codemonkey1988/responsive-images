<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Variant;

use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\PictureImageConfiguration;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class VariantFactoryTest extends UnitTestCase
{
    /**
     * @var bool
     */
    protected $resetSingletonInstances = true;

    /**
     * @test
     */
    public function createNewInstanceAndReceiveValidConfiguration(): void
    {
        $typoScript = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ];
        $configurationManagerMock = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManagerMock->method('getConfiguration')->willReturn($typoScript);

        $variantFactoryMock = $this->getMockBuilder(VariantFactory::class)
            ->setConstructorArgs([$configurationManagerMock])
            ->onlyMethods(['buildVariants', 'buildConfiguration'])
            ->getMock();
        $variantFactoryMock->method('buildVariants')->with(['foo' => 'bar']);
        $variantFactoryMock->method('buildConfiguration')->with(['foo' => 'bar']);
    }

    /**
     * @test
     */
    public function createNewInstanceAndThrowInvalidConfigurationException(): void
    {
        $configurationManagerMock = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManagerMock->method('getConfiguration')->willThrowException(
            new InvalidConfigurationTypeException()
        );

        $variantFactoryMock = $this->getMockBuilder(VariantFactory::class)
            ->setConstructorArgs([$configurationManagerMock])
            ->onlyMethods(['buildVariants', 'buildConfiguration'])
            ->getMock();
        $variantFactoryMock->method('buildVariants')->with([]);
        $variantFactoryMock->method('buildConfiguration')->with([]);
    }

    /**
     * @test
     */
    public function hasVariantReturnsTrue(): void
    {
        $factoryMock = $this->getMockBuilder(VariantFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        self::assertTrue($factoryMock->has('test'));
    }

    /**
     * @test
     */
    public function hasVariantReturnsFalse(): void
    {
        $factoryMock = $this->getMockBuilder(VariantFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();
        $factoryMock->method('get')->willThrowException(new NoSuchVariantException());

        self::assertFalse($factoryMock->has('test'));
    }

    /**
     * @test
     */
    public function getVariantKeyUseDefault(): void
    {
        $factoryMock = $this->getAccessibleMock(VariantFactory::class, ['has'], [], '', false);
        $key = $factoryMock->_call('getKeyFromRegistry');
        self::assertSame('default', $key);
    }

    /**
     * @test
     */
    public function getVariantKeyUseFromRegistry(): void
    {
        $GLOBALS['TSFE'] = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $GLOBALS['TSFE']->register['IMAGE_VARIANT_KEY'] = 'Test';
        $factoryMock = $this->getAccessibleMock(VariantFactory::class, ['has'], [], '', false);
        $key = $factoryMock->_call('getKeyFromRegistry');
        self::assertSame('Test', $key);
    }

    /**
     * @test
     */
    public function getVariantConfigurationByKeyAndThrowNoSuchVariantException(): void
    {
        $factoryMock = $this->getAccessibleMock(VariantFactory::class, ['getKeyFromRegistry'], [], '', false);
        $factoryMock->expects(self::never())->method('getKeyFromRegistry');
        self::expectException(NoSuchVariantException::class);
        $factoryMock->get('test');
    }

    /**
     * @test
     */
    public function getVariantConfigurationByKeyAndReturnConfiguration(): void
    {
        $factoryMock = $this->getAccessibleMock(VariantFactory::class, ['getKeyFromRegistry'], [], '', false);
        $factoryMock->expects(self::never())->method('getKeyFromRegistry');
        $factoryMock->_set('variants', [
            'test' => new Variant('test', [
                'foo' => 'bar',
            ])
        ]);
        $variant = $factoryMock->get('test');
        self::assertSame('bar', $variant->getConfig()['foo']);
    }

    /**
     * @test
     */
    public function createImageVariantFromTypoScript()
    {
        $typoScript = [
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'configuration.' => [
                            'default.' => [
                                'defaultWidth' => '1920',
                                'defaultHeight' => '1080',
                                'mimeTypes' => 'image/jpeg,image/gif,image/png',
                                'sources.' => [
                                    'small.' => [
                                        'media' => '(max-width: 767px)',
                                        'croppingVariantKey' => 'small',
                                        'sizes.' => [
                                            '1x.' => [
                                                'width' => '480',
                                                'height' => '320',
                                                'quality' => '65',
                                            ],
                                            '2x.' => [
                                                'width' => '960',
                                                'height' => '640',
                                                'quality' => '40',
                                            ],
                                        ],
                                    ],
                                    'large.' => [
                                        'media' => '(min-width: 768px)',
                                        'croppingVariantKey' => 'default',
                                        'sizes.' => [
                                            '1x.' => [
                                                'width' => '1920',
                                                'height' => '1080',
                                                'quality' => '80',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        self::expectDeprecation();

        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn($typoScript);
        $factory = new VariantFactory($configurationManager);
        $variant = $factory->get('default');

        self::assertInstanceOf(PictureImageConfiguration::class, $variant);
        self::assertSame('1920', $variant->getDefaultWidth());
        self::assertSame('1080', $variant->getDefaultHeight());
        self::assertSame(['image/jpeg', 'image/gif', 'image/png'], $variant->getMimeTypes());
        self::assertSame('(max-width: 767px)', $variant->getAllSourceConfig()[0]['media']);
        self::assertSame('small', $variant->getAllSourceConfig()[0]['croppingVariantKey']);
        self::assertSame('480', $variant->getAllSourceConfig()[0]['srcset']['1x']['width']);
        self::assertSame('320', $variant->getAllSourceConfig()[0]['srcset']['1x']['height']);
        self::assertSame('65', $variant->getAllSourceConfig()[0]['srcset']['1x']['quality']);
        self::assertSame('960', $variant->getAllSourceConfig()[0]['srcset']['2x']['width']);
        self::assertSame('640', $variant->getAllSourceConfig()[0]['srcset']['2x']['height']);
        self::assertSame('40', $variant->getAllSourceConfig()[0]['srcset']['2x']['quality']);
        self::assertSame('1920', $variant->getAllSourceConfig()[1]['srcset']['1x']['width']);
        self::assertSame('1080', $variant->getAllSourceConfig()[1]['srcset']['1x']['height']);
        self::assertSame('80', $variant->getAllSourceConfig()[1]['srcset']['1x']['quality']);
    }
}
