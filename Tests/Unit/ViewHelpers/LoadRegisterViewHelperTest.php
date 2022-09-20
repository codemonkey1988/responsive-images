<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\ViewHelpers;

use Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * @covers \Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper
 */
class LoadRegisterViewHelperTest extends UnitTestCase
{
    protected \stdClass $tsfe;

    /**
     * @var RenderingContextInterface
     */
    protected RenderingContextInterface $renderingContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tsfe = new \stdClass();
        $this->tsfe->registerStack = [];
        $this->tsfe->register = [];
        $GLOBALS['TSFE'] = $this->tsfe;
        $this->renderingContext = $this->getMockBuilder(RenderingContext::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersExpectedArguments(): void
    {
        $viewHelper = $this->getMockBuilder(LoadRegisterViewHelper::class)
            ->onlyMethods(['registerArgument'])
            ->getMock();
        $viewHelper->expects(self::exactly(2))
            ->method('registerArgument')
            ->withConsecutive(
                ['key', 'string', self::anything(), false],
                ['value', 'string', self::anything(), true]
            );
        $viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function registerStackIsSetAndKeptWhenNoContentIsRendered(): void
    {
        $arguments = [
            'key' => 'TEST_VARIABLE',
            'value' => 'Some value',
        ];
        $closure = \Closure::bind(static function () {
            return '';
        }, null);
        self::assertArrayNotHasKey('TEST_VARIABLE', $GLOBALS['TSFE']->register);
        LoadRegisterViewHelper::renderStatic($arguments, $closure, $this->renderingContext);
        self::assertEquals('Some value', $GLOBALS['TSFE']->register['TEST_VARIABLE']);
    }

    /**
     * @test
     */
    public function registerStackIsSetAndRemovedAfterContentIsRendered(): void
    {
        $arguments = [
            'key' => 'TEST_VARIABLE',
            'value' => 'Some value',
        ];
        $closure = \Closure::bind(static function () {
            UnitTestCase::assertSame('Some value', $GLOBALS['TSFE']->register['TEST_VARIABLE']);
            return '<content>';
        }, null);
        self::assertArrayNotHasKey('TEST_VARIABLE', $GLOBALS['TSFE']->register);
        LoadRegisterViewHelper::renderStatic($arguments, $closure, $this->renderingContext);
        self::assertArrayNotHasKey('TEST_VARIABLE', $GLOBALS['TSFE']->register);
    }

    /**
     * @test
     */
    public function childrenContentGivenIsRenderedAndReturned(): void
    {
        $arguments = [
            'key' => 'TEST_VARIABLE',
            'value' => 'Some value',
        ];
        $closure = \Closure::bind(static function () {
            return '<content>';
        }, null);
        $renderedContent = LoadRegisterViewHelper::renderStatic($arguments, $closure, $this->renderingContext);
        self::assertSame('<content>', $renderedContent);
    }
}
