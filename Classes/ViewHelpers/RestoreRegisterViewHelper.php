<?php

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage ViewHelpers
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
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