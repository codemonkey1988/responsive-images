<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Resource\Service;

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

use Codemonkey1988\ResponsiveImages\Resource\Service\ImageService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\File;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Service\ImageService
 */
class ImageServiceTest extends UnitTestCase
{
    /**
     * @var ImageService
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new ImageService();
    }

    /**
     * @test
     */
    public function isAnimtedGif()
    {
        $fileMock = $this->createConfiguredMock(
            File::class,
            [
                'getMimeType' => 'image/gif',
                'getForLocalProcessing' => __DIR__ . '/Assets/animated.gif',
            ]
        );

        self::assertTrue($this->subject->isAnimatedGif($fileMock));
    }

    /**
     * @test
     */
    public function isNotAnimatedGif()
    {
        $fileMock = $this->createConfiguredMock(
            File::class,
            [
                'getMimeType' => 'image/gif',
                'getForLocalProcessing' => __DIR__ . '/Assets/typo3.gif',
            ]
        );

        self::assertFalse($this->subject->isAnimatedGif($fileMock));
    }
}
