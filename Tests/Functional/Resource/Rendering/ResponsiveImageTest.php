<?php
namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Resource\Rendering;

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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureVariantsRegistry;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer
 */
class ResponsiveImageTest extends FunctionalTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $defaultVariant = new PictureImageVariant('default');
        $defaultVariant->setDefaultWidth(1920)
            ->addSourceConfig(
                '(max-width: 40em)',
                [
                    '1x' => ['width' => 320, 'quality' => 65],
                    '2x' => ['width' => 640, 'quality' => 40],
                ]
            )
            ->addSourceConfig(
                '(min-width: 64.0625em)',
                [
                    '1x' => ['width' => 1920],
                    '2x' => ['width' => 1920 * 2, 'quality' => 80],
                ]
            )
            ->addSourceConfig(
                '(min-width: 40.0625em)',
                [
                    '1x' => ['width' => 1024, 'quality' => 80],
                    '2x' => ['width' => 1024 * 2, 'quality' => 60],
                ]
            );

        $registry = PictureVariantsRegistry::getInstance();
        $registry->registerImageVariant($defaultVariant);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $registry = PictureVariantsRegistry::getInstance();
        $registry->removeAllImageVariants();
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function generateImageTagForValidJpegImage()
    {
        $processedFileMock = $this->createMock(ProcessedFile::class);
        $processedFileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['width', 720],
                    ['height', 500],
                ])
            );

        $fileMock = $this->createMock(File::class);
        $fileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['alernative', ''],
                    ['title', ''],
                ])
            );

        $subject = $this->getMockBuilder(ResponsiveImageRenderer::class)
            ->setMethods(['processImage', 'isAnimatedGif', 'getImageUri'])
            ->getMock();

        $subject->method('processImage')
            ->willReturn($processedFileMock);
        $subject->method('isAnimatedGif')
            ->willReturn(false);
        $subject
            ->method('getImageUri')
            ->willReturn('my-example-image.jpg');

        $result = $subject->render($fileMock, 0, 0);

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertRegExp('/<source media=".*" srcset=".*" \/>/', $result);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function generateImageTagForInvalidAnimatedGifImage()
    {
        $processedFileMock = $this->createMock(ProcessedFile::class);
        $processedFileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['width', 720],
                    ['height', 500],
                ])
            );

        $fileMock = $this->createMock(File::class);
        $fileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['alernative', ''],
                    ['title', ''],
                ])
            );

        $subject = $this->getMockBuilder(ResponsiveImageRenderer::class)
            ->setMethods(['processImage', 'isAnimatedGif', 'getImageUri'])
            ->getMock();

        $subject->method('processImage')
            ->willReturn($processedFileMock);
        $subject->method('isAnimatedGif')
            ->willReturn(true);
        $subject
            ->method('getImageUri')
            ->willReturn('my-animated-gif.gif');

        $result = $subject->render($fileMock, 0, 0);

        $this->assertSame('<img width="720" height="500" src="my-animated-gif.gif" />', $result);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function generateImageTagForValidJpegImageButDisabledPictureTag()
    {
        $processedFileMock = $this->createMock(ProcessedFile::class);
        $processedFileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['width', 720],
                    ['height', 500],
                ])
            );

        $fileMock = $this->createMock(File::class);
        $fileMock
            ->method('getProperty')
            ->will(
                $this->returnValueMap([
                    ['alernative', ''],
                    ['title', ''],
                ])
            );

        $subject = $this->getMockBuilder(ResponsiveImageRenderer::class)
            ->setMethods(['processImage', 'isAnimatedGif', 'getImageUri'])
            ->getMock();

        $subject->method('processImage')
            ->willReturn($processedFileMock);
        $subject->method('isAnimatedGif')
            ->willReturn(false);
        $subject
            ->method('getImageUri')
            ->willReturn('my-example-image.jpg');

        $result = $subject->render($fileMock, 0, 0, ['disablePictureTag' => true]);

        $this->assertSame('<img width="720" height="500" src="my-example-image.jpg" />', $result);
    }
}
