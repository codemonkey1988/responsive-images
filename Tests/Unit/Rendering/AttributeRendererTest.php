<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Rendering;

use Codemonkey1988\ResponsiveImages\Rendering\AttributeRenderer;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(AttributeRenderer::class)]
class AttributeRendererTest extends UnitTestCase
{
    protected MockObject $imageServiceMock;

    protected MockObject $eventDispatcherMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageServiceMock = $this->getMockBuilder(ImageService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['applyProcessingInstructions', 'getImageUri'])
            ->getMock();
        $this->eventDispatcherMock = $this->getMockBuilder(EventDispatcher::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['dispatch'])
            ->getMock();
        $this->eventDispatcherMock->method('dispatch')->willReturnArgument(0);
    }

    #[Test]
    public function renderSrcsetReturnAttributeValue(): void
    {
        $fileMock = $this->createMock(File::class);
        $this->imageServiceMock->method('applyProcessingInstructions')->willReturnOnConsecutiveCalls(
            $this->buildProcessedFileMock(500),
            $this->buildProcessedFileMock(1000),
            $this->buildProcessedFileMock(2000)
        );
        $this->imageServiceMock->method('getImageUri')->willReturnOnConsecutiveCalls(
            '500.jpg',
            '1000.jpg',
            '2000.jpg'
        );
        $variantMock = $this->getMockBuilder(Variant::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfig'])
            ->getMock();
        $variantMock->method('getConfig')->willReturn([]);
        $attributeRendererMock = $this->getMockBuilder(AttributeRenderer::class)
            ->setConstructorArgs([$this->imageServiceMock, $this->eventDispatcherMock])
            ->onlyMethods(['buildProcessingInstructions'])
            ->getMock();
        $attributeRendererMock->method('buildProcessingInstructions')->willReturn([
            'smartphone' => [
                'maxWidth' => 500,
            ],
            'tablet' => [
                'maxWidth' => 1000,
            ],
            'desktop' => [
                'maxWidth' => 2000,
            ],
        ]);
        $srcsetResult = $attributeRendererMock->renderSrcset($fileMock, $variantMock);
        self::assertSame(
            '500.jpg 500w, 1000.jpg 1000w, 2000.jpg 2000w',
            $srcsetResult
        );
    }

    #[Test]
    public function renderSizesReturnAttributeValue(): void
    {
        $variantMock = $this->getMockBuilder(Variant::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConfig'])
            ->getMock();
        $variantMock->method('getConfig')->willReturn([
            'sizes.' => [
                '10.' => [
                    'viewportMediaCondition' => '(min-width: 768px)',
                    'assumedImageWidth' => '1000px',
                ],
                '20.' => [
                    'viewportMediaCondition' => '(min-width: 1024px)',
                    'assumedImageWidth' => '2000px',
                ],
                '30.' => [
                    'assumedImageWidth' => '500px',
                ],
            ],
        ]);
        $attributeRendererMock = $this->getMockBuilder(AttributeRenderer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['buildProcessingInstructions'])
            ->getMock();
        $sizesResult = $attributeRendererMock->renderSizes($variantMock);
        self::assertSame(
            '(min-width: 768px) 1000px, (min-width: 1024px) 2000px, 500px',
            $sizesResult
        );
    }

    protected function buildProcessedFileMock(int $resultWidth): MockObject
    {
        $processedFileMock = $this->getMockBuilder(ProcessedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
        $processedFileMock->method('getProperty')->willReturn($resultWidth);
        return $processedFileMock;
    }
}
