<?php
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

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Codemonkey1988\ResponsiveImages\ViewHelpers\RestoreRegisterViewHelper;

/**
 * Test class \Codemonkey1988\ResponsiveImages\ViewHelpers\RestoreRegisterViewHelper
 */
class RestoreRegisterViewHelperTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $tsfe = null;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->tsfe      = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            ['dummy'],
            [],
            '',
            false
        );
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * Test if the RestoreRegisterViewHelper resets the registry stack.
     *
     * @test
     * @return void
     * @throws \PHPUnit_Framework_Exception
     */
    public function variableIsRestored()
    {
        $registerVariableName  = 'TEST_VARIABLE';
        $registerVariableValue = 'Some value';

        array_push($GLOBALS['TSFE']->registerStack, $GLOBALS['TSFE']->register);
        $GLOBALS['TSFE']->register[$registerVariableName] = $registerVariableValue;

        $this->assertEquals($registerVariableValue, $GLOBALS['TSFE']->register[$registerVariableName]);

        /** @var RestoreRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(RestoreRegisterViewHelper::class, ['renderChildren']);
        $viewHelper->render();

        $this->assertArrayNotHasKey($registerVariableName, $GLOBALS['TSFE']->register);
    }
}
