<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Resource\Service;

use Codemonkey1988\ResponsiveImages\Resource\Service\ImageService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Service\ImageService
 */
class ImageServiceTest extends UnitTestCase
{
    /**
     * @var ImageService
     */
    protected $subject;

    protected function setUp(): void
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
                'getContents' => file_get_contents(__DIR__ . '/Assets/animated.gif'),
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
                'getContents' => file_get_contents(__DIR__ . '/Assets/typo3.gif'),
            ]
        );

        self::assertFalse($this->subject->isAnimatedGif($fileMock));
    }
}
