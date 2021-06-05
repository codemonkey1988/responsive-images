<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to add a key-value-pair to TYPO3 register stack.
 */
class LoadRegisterViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('key', 'string', 'Key for adding value to register stack', true);
        $this->registerArgument('value', 'string', 'Value that should be added to register stack', true);
    }

    /**
     * Renders the viewhelper.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $key = $arguments['key'];
        $value = $arguments['value'];

        array_push($GLOBALS['TSFE']->registerStack, $GLOBALS['TSFE']->register);
        $GLOBALS['TSFE']->register[$key] = $value;

        $content = $renderChildrenClosure();

        if ($content) {
            // Restore register when content was rendered
            $GLOBALS['TSFE']->register = array_pop($GLOBALS['TSFE']->registerStack);

            return $content;
        }

        return '';
    }
}
