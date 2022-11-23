<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Fluid\View\TemplateView;

/**
 * @covers \Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper
 */
class LoadRegisterViewHelperTest extends ViewHelperTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->registerStack = [];
        $GLOBALS['TSFE']->register = [];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function renderingViewHelperWithoutContentWillSetRegisterDataProvider(): array
    {
        return  [
            'Only set value' => [
                'template' => '<r:loadRegister value="my-value" />',
                'expectedKey' => 'IMAGE_VARIANT_KEY',
                'expectedValue' => 'my-value',
            ],
            'Set key and value' => [
                'template' => '<r:loadRegister key="MY_KEY" value="my-value" />',
                'expectedKey' => 'MY_KEY',
                'expectedValue' => 'my-value',
            ]
        ];
    }

    /**
     * @test
     * @dataProvider renderingViewHelperWithoutContentWillSetRegisterDataProvider
     */
    public function renderingViewHelperWithoutContentWillSetRegister(
        string $template,
        string $expectedKey,
        string $expectedValue
    ): void {
        $context = $this->buildRenderingContext();
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        self::assertArrayNotHasKey($expectedKey, $GLOBALS['TSFE']->register);
        $templateView->render();
        self::assertEquals($expectedValue, $GLOBALS['TSFE']->register[$expectedKey]);
    }

    /**
     * @test
     */
    public function registerStackIsSetAndRemovedAfterContentIsRendered(): void
    {
        $template = '<r:loadRegister value="my-value">Test</r:loadRegister>';
        $context = $this->buildRenderingContext();
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        self::assertArrayNotHasKey('IMAGE_VARIANT_KEY', $GLOBALS['TSFE']->register);
        $templateView->render();
        self::assertArrayNotHasKey('IMAGE_VARIANT_KEY', $GLOBALS['TSFE']->register);
    }

    /**
     * @test
     */
    public function childrenContentGivenIsRenderedAndReturned(): void
    {
        $template = '<r:loadRegister value="my-value">Test</r:loadRegister>';
        $context = $this->buildRenderingContext();
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        self::assertSame('Test', $templateView->render());
    }
}
