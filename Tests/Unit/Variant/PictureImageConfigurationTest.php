<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Variant;

use Codemonkey1988\ResponsiveImages\Variant\PictureImageConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant
 */
class PictureImageConfigurationTest extends UnitTestCase
{
    /**
     * Test if the default width can be set.
     *
     * @test
     */
    public function settingDefaultWidthWillWork()
    {
        self::expectDeprecation();

        /** @var PictureImageConfiguration|MockObject $pictureImagePictureImageConfiguration */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->setDefaultWidth('2000');

        self::assertEquals('2000', $pictureImageVariant->getDefaultWidth());
    }

    /**
     * Test if the default height can be set.
     *
     * @test
     */
    public function settingDefaultHeightWillWork()
    {
        self::expectDeprecation();

        /** @var PictureImageConfiguration|MockObject $pictureImageVariant */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->setDefaultHeight('700');

        self::assertEquals('700', $pictureImageVariant->getDefaultHeight());
    }

    /**
     * Tests if a source configuration can be added.
     *
     * @test
     */
    public function addSingleSourceConfig()
    {
        self::expectDeprecation();

        $media = '(max-width: 64em)';
        $srcsets = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $expected = [
            0 => [
                'media' => $media,
                'srcset' => $srcsets,
                'croppingVariantKey' => 'default',
            ],
        ];

        /** @var PictureImageConfiguration|MockObject $pictureImageVariant */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->addSourceConfig($media, $srcsets);

        self::assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        self::assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }

    /**
     * Tests if a multiple source configurations can be added.
     *
     * @test
     */
    public function addMultipleSourceConfig()
    {
        self::expectDeprecation();

        $media1 = '(max-width: 64em)';
        $media2 = '(max-width: 40em)';
        $srcset1 = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $srcset2 = [
            '1x' => ['width' => '360c', 'height' => '200c', 'quality' => '50'],
            '2x' => ['width' => '7200c', 'height' => '400c', 'quality' => '60'],
        ];
        $expected = [
            0 => [
                'media' => $media1,
                'srcset' => $srcset1,
                'croppingVariantKey' => 'default',
            ],
            1 => [
                'media' => $media2,
                'srcset' => $srcset2,
                'croppingVariantKey' => 'default',
            ],
        ];

        /** @var PictureImageConfiguration|MockObject $pictureImageVariant */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->addSourceConfig($media1, $srcset1);
        $pictureImageVariant->addSourceConfig($media2, $srcset2);

        self::assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        self::assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }

    /**
     * Tests if a source configuration can be added with a custo mcropping variant key
     *
     * @test
     */
    public function addSingleSourceConfigWithCroppingVariantKey()
    {
        self::expectDeprecation();

        $croppingVariantKey = 'mobile';
        $media = '(max-width: 64em)';
        $srcsets = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $expected = [
            0 => [
                'media' => $media,
                'srcset' => $srcsets,
                'croppingVariantKey' => $croppingVariantKey,
            ],
        ];

        /** @var PictureImageConfiguration|MockObject $pictureImageVariant */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->addSourceConfig($media, $srcsets, $croppingVariantKey);

        self::assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        self::assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }

    /**
     * Tests if a multiple source configurations can be added.
     *
     * @test
     */
    public function addMultipleSourceConfigWithCroppingVariantKey()
    {
        self::expectDeprecation();

        $croppingVariantKey1 = 'mobile';
        $croppingVariantKey2 = 'desktop';
        $media1 = '(max-width: 64em)';
        $media2 = '(max-width: 40em)';
        $srcset1 = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $srcset2 = [
            '1x' => ['width' => '360c', 'height' => '200c', 'quality' => '50'],
            '2x' => ['width' => '7200c', 'height' => '400c', 'quality' => '60'],
        ];
        $expected = [
            0 => [
                'media' => $media1,
                'srcset' => $srcset1,
                'croppingVariantKey' => $croppingVariantKey1,
            ],
            1 => [
                'media' => $media2,
                'srcset' => $srcset2,
                'croppingVariantKey' => $croppingVariantKey2,
            ],
        ];

        /** @var PictureImageConfiguration|MockObject $pictureImageVariant */
        $pictureImageVariant = new PictureImageConfiguration('test', []);
        $pictureImageVariant->addSourceConfig($media1, $srcset1, $croppingVariantKey1);
        $pictureImageVariant->addSourceConfig($media2, $srcset2, $croppingVariantKey2);

        self::assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        self::assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }
}
