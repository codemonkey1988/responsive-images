<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Fluid\View\TemplateView;

/**
 * @covers \Codemonkey1988\ResponsiveImages\ViewHelpers\IfAnimatedGifViewHelper
 */
class IfAnimatedGifViewHelperTest extends ViewHelperTestCase
{
    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'typo3conf/ext/responsive_images',
        ];
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/BeUsers.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileStorage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFile.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileMetadata.csv');

        $this->setUpBackendUser(1);
    }

    /**
     * @test
     */
    public function givenJpgFileWillRenderElsePart(): void
    {
        $image =  $this->get(FileRepository::class)->findByUid(1);
        $template = '<r:ifAnimatedGif image="{image}"><f:then>Animated</f:then><f:else>Not animated</f:else></r:ifAnimatedGif>';
        $context = $this->buildRenderingContext();
        $context->getVariableProvider()->add('image', $image);
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        $renderedTag = $templateView->render();

        self::assertSame('Not animated', $renderedTag);
    }

    /**
     * @test
     */
    public function givenAnimatedGifFileWillRenderThenPart(): void
    {
        $image =  $this->get(FileRepository::class)->findByUid(2);
        $template = '<r:ifAnimatedGif image="{image}"><f:then>Animated</f:then><f:else>Not animated</f:else></r:ifAnimatedGif>';
        $context = $this->buildRenderingContext();
        $context->getVariableProvider()->add('image', $image);
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        $renderedTag = $templateView->render();

        self::assertSame('Animated', $renderedTag);
    }
}
