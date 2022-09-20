<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\ViewHelpers;

use Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper
 */
class LoadRegisterViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var MockObject|TypoScriptFrontendController
     */
    protected $tsfe;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tsfe = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersExpectedArguments()
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
     * Test if the variable is set via the viewhelper.
     *
     * @test
     */
    public function variableIsSet()
    {
        $registerVariableName = 'TEST_VARIABLE';
        $registerVariableValue = 'Some value';

        $viewHelper = $this->getMockBuilder(LoadRegisterViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $this->injectDependenciesIntoViewHelper($viewHelper);
        $viewHelper->setArguments([
            'key' => $registerVariableName,
            'value' => $registerVariableValue,
        ]);
        $viewHelper->render();

        self::assertEquals($registerVariableValue, $GLOBALS['TSFE']->register[$registerVariableName]);
    }

    /**
     * @test
     */
    public function childrenBeingRendered()
    {
        $viewHelper = $this->getMockBuilder(LoadRegisterViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn('<content>');
        $this->injectDependenciesIntoViewHelper($viewHelper);
        $viewHelper->setArguments([
            'key' => 'foo',
            'value' => 'bar',
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function variableIsSetInChildrenContext()
    {
        $registerVariableName = 'TEST_VARIABLE';
        $registerVariableValue = 'Some value';

        $viewHelper = $this->getMockBuilder(LoadRegisterViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::any())->method('renderChildren')->willReturnCallback(function () use ($registerVariableName, $registerVariableValue) {
            $this->assertEquals($registerVariableValue, $GLOBALS['TSFE']->register[$registerVariableName]);
        });
        $this->injectDependenciesIntoViewHelper($viewHelper);
        $viewHelper->setArguments([
            'key' => $registerVariableName,
            'value' => $registerVariableValue,
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function variableIsUnsetAfterChildrenRendered()
    {
        $registerVariableName = 'TEST_VARIABLE';

        $viewHelper = $this->getMockBuilder(LoadRegisterViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::any())->method('renderChildren')->willReturn('<content>');
        $this->injectDependenciesIntoViewHelper($viewHelper);
        $viewHelper->setArguments([
            'key' => $registerVariableName,
            'value' => 'bar',
        ]);
        $viewHelper->render();

        self::assertArrayNotHasKey($registerVariableName, $GLOBALS['TSFE']->register);
    }
}
