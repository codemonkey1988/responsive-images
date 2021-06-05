<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\ViewHelpers;

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

use Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper;
use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper
 */
class LoadRegisterViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected $tsfe;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->tsfe = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            ['dummy'],
            [],
            '',
            false
        );
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersExpectedArguments()
    {
        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(LoadRegisterViewHelper::class, ['registerArgument']);
        $viewHelper->expects(self::at(0))->method('registerArgument')->with('key', 'string', self::anything(), true);
        $viewHelper->expects(self::at(1))->method('registerArgument')->with('value', 'string', self::anything(), true);
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

        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(
            LoadRegisterViewHelper::class,
            ['renderChildren']
        );
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
        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(LoadRegisterViewHelper::class, ['renderChildren']);
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

        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(LoadRegisterViewHelper::class, ['renderChildren']);
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

        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(LoadRegisterViewHelper::class, ['renderChildren']);
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
