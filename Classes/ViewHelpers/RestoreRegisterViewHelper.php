<?php
namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to remove a stack from the TYPO3 register stack.
 */
class RestoreRegisterViewHelper extends AbstractViewHelper
{
    /**
     * Renders the viewhelper.
     *
     * @return void
     */
    public function render()
    {
        $GLOBALS['TSFE']->register = array_pop($GLOBALS['TSFE']->registerStack);
    }
}
