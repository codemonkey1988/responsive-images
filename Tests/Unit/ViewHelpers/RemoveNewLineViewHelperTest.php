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

use Codemonkey1988\ResponsiveImages\ViewHelpers\RemoveNewLineViewHelper;
use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;

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
        /** @var RemoveNewLineViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(RemoveNewLineViewHelper::class, ['renderChildren']);
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn("\n<content>\n\n</content>\n");
        $this->injectDependenciesIntoViewHelper($viewHelper);

        self::assertSame('<content></content>', $viewHelper->render());
    }
}
