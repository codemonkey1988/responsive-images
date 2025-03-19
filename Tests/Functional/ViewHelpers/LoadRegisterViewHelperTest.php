<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Tests\Functional\ServerRequestTrait;
use Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

#[CoversClass(LoadRegisterViewHelper::class)]
class LoadRegisterViewHelperTest extends ViewHelperTestCase
{
    use ServerRequestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $typo3Version = new Typo3Version();
        if ($typo3Version->getMajorVersion() > 12) {
            $GLOBALS['TSFE'] = new TypoScriptFrontendController();
        } else {
            $site = new Site('test', 1, []);
            $GLOBALS['TSFE'] = new TypoScriptFrontendController(
                $this->get(Context::class),
                $site,
                $site->getDefaultLanguage(),
                new PageArguments(1, '0', []),
                new FrontendUserAuthentication(),
            );
        }

        $GLOBALS['TSFE']->registerStack = [];
        $GLOBALS['TSFE']->register = [];

        $GLOBALS['TYPO3_REQUEST'] = $this->buildFakeServerRequest();
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function renderingViewHelperWithoutContentWillSetRegisterDataProvider(): array
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

    #[Test]
    #[DataProvider('renderingViewHelperWithoutContentWillSetRegisterDataProvider')]
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

    #[Test]
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

    #[Test]
    public function childrenContentGivenIsRenderedAndReturned(): void
    {
        $template = '<r:loadRegister value="my-value">Test</r:loadRegister>';
        $context = $this->buildRenderingContext();
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        self::assertSame('Test', $templateView->render());
    }
}
