<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\ViewHelpers;

use Codemonkey1988\ResponsiveImages\ViewHelpers\RemoveNewLineViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\ViewHelpers\RemoveNewLineViewHelper
 */
class RemoveNewLineViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @test
     */
    public function newLinesAreRemoved()
    {
        $viewHelper = $this->getMockBuilder(RemoveNewLineViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn("\n<content>\n\n</content>\n");
        $this->injectDependenciesIntoViewHelper($viewHelper);

        self::assertSame('<content></content>', $viewHelper->render());
    }
}
