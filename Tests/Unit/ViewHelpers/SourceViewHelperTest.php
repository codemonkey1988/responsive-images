<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Rendering\AttributeRenderer;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use Codemonkey1988\ResponsiveImages\ViewHelpers\SourceViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * @covers \Codemonkey1988\ResponsiveImages\ViewHelpers\SourceViewHelper
 */
class SourceViewHelperTest extends UnitTestCase
{
    protected MockObject $variantFactory;

    protected MockObject $attributeRenderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->variantFactory = $this->getMockBuilder(VariantFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['has', 'get'])
            ->getMock();
        $this->attributeRenderer = $this->getMockBuilder(AttributeRenderer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['renderSrcset', 'renderSizes'])
            ->getMock();
    }

    /**
     * @test
     */
    public function renderGivenVariantWithSrcsetAndSizes(): void
    {
        $variant = new Variant('default', [
            'media' => '(min-width:465px)',
        ]);
        $this->variantFactory->method('get')->willReturn($variant);
        $this->attributeRenderer->method('renderSrcset')->willReturn('image.jpg 1000w');
        $this->attributeRenderer->method('renderSizes')->willReturn(
            '(min-width: 971px) 585px, (min-width: 751px) 485px, (min-width: 421px) 375px, 420px'
        );

        $sourceViewHelperMock = $this->getAccessibleMock(SourceViewHelper::class, ['initializeArguments']);
        $sourceViewHelperMock->_set('arguments', [
            'srcsetVariantKey' => 'default',
            'image' => $this->buildImageMock(),
        ]);
        $sourceViewHelperMock->_call('setTagBuilder', new TagBuilder('source'));
        $sourceViewHelperMock->_call('setVariantFactory', $this->variantFactory);
        $sourceViewHelperMock->_call('setAttributeRenderer', $this->attributeRenderer);
        $renderedTag = $sourceViewHelperMock->_call('render');
        self::assertSame(
            '<source type="image/jpeg" srcset="image.jpg 1000w" media="(min-width:465px)" sizes="(min-width: 971px) 585px, (min-width: 751px) 485px, (min-width: 421px) 375px, 420px" />',
            $renderedTag
        );
    }

    protected function buildImageMock(string $mimeType = 'image/jpeg'): MockObject
    {
        $image = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMimeType'])
            ->getMock();
        $image->method('getMimeType')->willReturn($mimeType);
        return $image;
    }
}
