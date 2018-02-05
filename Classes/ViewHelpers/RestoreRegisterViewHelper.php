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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to remove a stack from the TYPO3 register stack.
 *
 * @deprecated Use LoadRegister ViewHelper instead and render content es children.
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
        GeneralUtility::deprecationLog(sprintf('The ViewHelper "%s" has been deprecated and will be removed with 1.5.0 of EXT:responsive_images', self::class));

        $GLOBALS['TSFE']->register = array_pop($GLOBALS['TSFE']->registerStack);
    }
}
