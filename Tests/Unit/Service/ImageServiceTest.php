<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Service;

use Codemonkey1988\ResponsiveImages\Service\ImageService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(ImageService::class)]
class ImageServiceTest extends UnitTestCase
{
    #[Test]
    public function isAnimatedGif(): void
    {
        $imageService = new ImageService();
        $fileMock = $this->createConfiguredMock(
            File::class,
            [
                'getMimeType' => 'image/gif',
                'getForLocalProcessing' => __DIR__ . '/../Fixtures/animated.gif',
            ]
        );

        self::assertTrue($imageService->isAnimatedGif($fileMock));
    }

    #[Test]
    public function isNotAnimatedGif(): void
    {
        $imageService = new ImageService();
        $fileMock = $this->createConfiguredMock(
            File::class,
            [
                'getMimeType' => 'image/gif',
                'getForLocalProcessing' => __DIR__ . '/../Fixtures/typo3.gif',
            ]
        );

        self::assertFalse($imageService->isAnimatedGif($fileMock));
    }
}
