<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

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
        $this->registerArgument(
            'key',
            'string',
            'Key for adding value to register stack',
            false,
            'IMAGE_VARIANT_KEY'
        );
        $this->registerArgument(
            'value',
            'string',
            'Value that should be added to register stack',
            true
        );
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
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
