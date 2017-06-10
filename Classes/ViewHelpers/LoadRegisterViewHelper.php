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
 * ViewHelper to add a key-value-pair to TYPO3 register stack.
 */
class LoadRegisterViewHelper extends AbstractViewHelper
{
    /**
     * Renders the viewhelper.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function render($key, $value)
    {
        array_push($GLOBALS['TSFE']->registerStack, $GLOBALS['TSFE']->register);
        $GLOBALS['TSFE']->register[$key] = $value;
    }
}
