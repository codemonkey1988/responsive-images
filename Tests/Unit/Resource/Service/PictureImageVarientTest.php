<?php

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Resource\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant;

/**
 * Class GeneralUtilityTest
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Tests\Unit\ViewHelpers
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class GeneralUtilityTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Test if the default width can be set.
     *
     * @test
     * @return void
     */
    public function testDefaultWidth()
    {
        /** @var PictureImageVariant|\PHPUnit_Framework_MockObject_MockObject $pictureImageVariant */
        $pictureImageVariant = $this->getAccessibleMock(PictureImageVariant::class, ['__construct'], ['test']);
        $pictureImageVariant->setDefaultWidth('2000');

        $this->assertEquals('2000', $pictureImageVariant->getDefaultWidth());
    }

    /**
     * Test if the default height can be set.
     *
     * @test
     * @return void
     */
    public function testDefaultHeight()
    {
        /** @var PictureImageVariant|\PHPUnit_Framework_MockObject_MockObject $pictureImageVariant */
        $pictureImageVariant = $this->getAccessibleMock(PictureImageVariant::class, ['__construct'], ['test']);
        $pictureImageVariant->setDefaultHeight('700');

        $this->assertEquals('700', $pictureImageVariant->getDefaultHeight());
    }

    /**
     * Tests if a source configuration can be added.
     *
     * @test
     * @return void
     */
    public function testAddSingleSourceConfig()
    {
        $media    = '(max-width: 64em)';
        $srcsets  = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $expected = [
            0 => [
                'media'  => $media,
                'srcset' => $srcsets
            ]
        ];

        /** @var PictureImageVariant|\PHPUnit_Framework_MockObject_MockObject $pictureImageVariant */
        $pictureImageVariant = $this->getAccessibleMock(PictureImageVariant::class, ['__construct'], ['test']);
        $pictureImageVariant->addSourceConfig($media, $srcsets);

        $this->assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        $this->assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }

    /**
     * Tests if a multiple source configurations can be added.
     *
     * @test
     * @return void
     */
    public function testAddMultipleSourceConfig()
    {
        $media1   = '(max-width: 64em)';
        $media2   = '(max-width: 40em)';
        $srcset1  = [
            '1x' => ['width' => '1280c', 'height' => '600c', 'quality' => '80'],
            '2x' => ['width' => '2560c', 'height' => '1200c', 'quality' => '50'],
        ];
        $srcset2  = [
            '1x' => ['width' => '360c', 'height' => '200c', 'quality' => '50'],
            '2x' => ['width' => '7200c', 'height' => '400c', 'quality' => '60'],
        ];
        $expected = [
            0 => [
                'media'  => $media1,
                'srcset' => $srcset1
            ],
            1 => [
                'media'  => $media2,
                'srcset' => $srcset2
            ]
        ];

        /** @var PictureImageVariant|\PHPUnit_Framework_MockObject_MockObject $pictureImageVariant */
        $pictureImageVariant = $this->getAccessibleMock(PictureImageVariant::class, ['__construct'], ['test']);
        $pictureImageVariant->addSourceConfig($media1, $srcset1);
        $pictureImageVariant->addSourceConfig($media2, $srcset2);

        $this->assertTrue(is_array($pictureImageVariant->getAllSourceConfig()));
        $this->assertEquals($expected, $pictureImageVariant->getAllSourceConfig());
    }
}