<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to add a key-value-pair to TYPO3 register stack.
 */
class LoadRegisterViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
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

    public function render(): string
    {
        $key = $this->arguments['key'];
        $value = $this->arguments['value'];
        $contentObjectRenderer = $this->getContentObjectRenderer();
        $contentObjectRenderer->cObjGetSingle('LOAD_REGISTER', [
            $key => $value
        ]);
        $content = $this->renderChildren();
        if ($content) {
            // Restore register when content was rendered
            $contentObjectRenderer->cObjGetSingle('RESTORE_REGISTER', []);
            return $content;
        }
        return '';
    }

    private function getContentObjectRenderer(): ContentObjectRenderer
    {
        $contentObjectRenderer = $this->getTypo3Request()->getAttribute('currentContentObject');
        if ($contentObjectRenderer === null) {
            throw new \RuntimeException('Missing content object renderer in request attribute.', 1742394213);
        }

        return $contentObjectRenderer;
    }

    private function getTypo3Request(): ServerRequestInterface
    {
        if ($this->renderingContext->hasAttribute(ServerRequestInterface::class)) {
            return $this->renderingContext->getAttribute(ServerRequestInterface::class);
        }

        // Fallback for TYPO3 v12
        if (method_exists($this->renderingContext, 'getRequest')) {
            $request = $this->renderingContext->getRequest();
            if ($request !== null) {
                return $request;
            }
        }

        throw new \RuntimeException('Missing server request object.', 1742392712);
    }
}
