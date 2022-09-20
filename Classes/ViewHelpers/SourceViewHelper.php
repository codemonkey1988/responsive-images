<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Exception;
use Codemonkey1988\ResponsiveImages\Rendering\AttributeRenderer;
use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use TYPO3\CMS\Core\Resource\File;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class SourceViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'source';

    protected VariantFactory $variantFactory;

    protected AttributeRenderer $attributeRenderer;

    /**
     * @required
     */
    public function setVariantFactory(VariantFactory $variantFactory): void
    {
        $this->variantFactory = $variantFactory;
    }

    /**
     * @required
     */
    public function setAttributeRenderer(AttributeRenderer $attributeRenderer): void
    {
        $this->attributeRenderer = $attributeRenderer;
    }

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('image', 'object', 'A FAL image object', true);
        $this->registerArgument('srcsetVariantKey', 'string', 'Render an srcset attribute by using the variant config for the given key.', false);
        $this->registerArgument('cropVariantKey', 'string', 'Use the given crop variant for srcset rendering.', false, 'default');
    }

    /**
     * @throws Exception|NoSuchVariantException
     * @return string Rendered tag
     */
    public function render()
    {
        $variant = $this->variantFactory->get($this->arguments['srcsetVariantKey']);
        $media = $variant->getConfig()['media'] ?? '';
        $sizes = $this->attributeRenderer->renderSizes($variant);
        /** @var File $image */
        $image = $this->arguments['image'];
        if (is_callable([$image, 'getOriginalResource'])) {
            $image = $image->getOriginalResource();
        }

        $this->tag->addAttribute('type', $variant->getConfig()['type'] ?? $image->getMimeType());
        $this->tag->addAttribute('srcset', $this->attributeRenderer->renderSrcset(
            $image,
            $variant,
            $this->arguments['cropVariantKey']
        ));
        if (strlen($media) > 0) {
            $this->tag->addAttribute('media', $media);
        }
        if (strlen($sizes) > 0) {
            $this->tag->addAttribute('sizes', $sizes);
        }

        return $this->tag->render();
    }
}
