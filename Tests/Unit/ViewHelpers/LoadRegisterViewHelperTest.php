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

use \TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\ViewHelpers\LoadRegisterViewHelper
 */
class LoadRegisterViewHelperTest extends UnitTestCase
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
     * Test if the variable is set via the viewhelper.
     *
     * @test
     * @return void
     */
    public function variableIsSet()
    {
        $registerVariableName  = 'TEST_VARIABLE';
        $registerVariableValue = 'Some value';

        /** @var LoadRegisterViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(LoadRegisterViewHelper::class, ['renderChildren']);
        $viewHelper->render($registerVariableName, $registerVariableValue);

        $this->assertEquals($registerVariableValue, $GLOBALS['TSFE']->register[$registerVariableName]);
    }
}
