<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Resource\Variant;

use Codemonkey1988\ResponsiveImages\Resource\Variant\PictureImageVariant;
use Codemonkey1988\ResponsiveImages\Resource\Variant\PictureImageVariantFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PictureImageVariantFactoryTest extends UnitTestCase
{
    /**
     * @var bool
     */
    protected $resetSingletonInstances = true;

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
                                'defaultWidth.' => [
                                    'intval' => '1',
                                ],
                                'defaultHeight' => '1080',
                                'defaultHeight.' => [
                                    'intval' => '1',
                                ],
                                'mimeTypes' => 'image/jpeg,image/gif,image/png',
                                'sources.' => [
                                    'small.' => [
                                        'media' => '(max-width: 767px)',
                                        'croppingVariantKey' => 'small',
                                        'sizes.' => [
                                            '1x.' => [
                                                'width' => '480',
                                                'width.' => [
                                                    'intval' => '1',
                                                ],
                                                'height' => '320',
                                                'height.' => [
                                                    'intval' => '1',
                                                ],
                                                'quality' => '65',
                                            ],
                                            '2x.' => [
                                                'width' => '480*2',
                                                'width.' => [
                                                    'intval' => '1',
                                                    'prioriCalc' => '1'
                                                ],
                                                'height' => '320*2',
                                                'height.' => [
                                                    'intval' => '1',
                                                    'prioriCalc' => '1',
                                                ],
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
                                                'width.' => [
                                                    'intval' => '1',
                                                ],
                                                'height' => '1080',
                                                'height.' => [
                                                    'intval' => '1',
                                                ],
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

        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->method('getConfiguration')->willReturn($typoScript);
        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $factory = new PictureImageVariantFactory($contentObjectRenderer, $configurationManager);
        $variant = $factory->get('default');

        self::assertInstanceOf(PictureImageVariant::class, $variant);
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
