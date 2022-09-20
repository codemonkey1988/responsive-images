<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Rendering;

use Codemonkey1988\ResponsiveImages\Service\ConfigurationService;
use Codemonkey1988\ResponsiveImages\Service\ImageService;
use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\PictureImageConfiguration;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class to render a picture tag with different sources and a fallback image.
 * @deprecated This class is deprecated and will be removed in 4.0. Please migrate your configuration to variants.
 */
class ResponsiveImageRenderer implements FileRendererInterface
{
    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';
    const REGISTER_IMAGE_RELATIVE_WIDTH_KEY = 'IMAGE_RELATIVE_WIDTH_KEY';
    const OPTIONS_IMAGE_RELATIVE_WIDTH_KEY = 'relativeScalingWidth';

    /**
     * @var ConfigurationService
     */
    protected ConfigurationService $configurationService;

    /**
     * @var EnvironmentService
     */
    protected EnvironmentService $environmentService;

    /**
     * @var ImageService
     */
    protected ImageService $imageService;

    /**
     * @var VariantFactory
     */
    protected VariantFactory $variantFactory;

    /**
     * @param ConfigurationService $configurationService
     * @param EnvironmentService $environmentService
     * @param ImageService $imageService
     * @param VariantFactory $variantFactory
     */
    public function __construct(
        ConfigurationService $configurationService,
        EnvironmentService $environmentService,
        ImageService $imageService,
        VariantFactory $variantFactory
    ) {
        $this->configurationService = $configurationService;
        $this->environmentService = $environmentService;
        $this->imageService = $imageService;
        $this->variantFactory = $variantFactory;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 5;
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        try {
            $config = $this->getConfig();
        } catch (NoSuchVariantException $e) {
            return false;
        }
        return $this->configurationService->isEnabled()
            && $this->environmentService->isEnvironmentInFrontendMode()
            && in_array($file->getMimeType(), $config->getMimeTypes());
    }

    /**
     * Renders a responsive image tag.
     *
     * @param FileInterface $file
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false): string
    {
        if (!array_key_exists(self::OPTIONS_IMAGE_RELATIVE_WIDTH_KEY, $options)
            && isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATIVE_WIDTH_KEY])
        ) {
            $options[self::OPTIONS_IMAGE_RELATIVE_WIDTH_KEY] = (float)$GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATIVE_WIDTH_KEY];
        }
        try {
            $config = $this->getConfig();
        } catch (NoSuchVariantException $e) {
            $config = null;
        }
        $view = $this->initializeView();
        $view->assignMultiple([
            'isAnimatedGif' => $this->isAnimatedGif($file),
            'config' => $config,
            'data' => $GLOBALS['TSFE']->cObj->data,
            'file' => $file,
            'options' => $options,
        ]);
        return $view->render('pictureTag');
    }

    /**
     * @return StandaloneView
     */
    protected function initializeView(): StandaloneView
    {
        /** @var StandaloneView $view */
        $view = GeneralUtility::makeInstance(StandaloneView::class, $GLOBALS['TSFE']->cObj);
        if (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.'])) {
            $view->setTemplateRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['templateRootPaths.']);
            $view->setPartialRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['partialRootPaths.']);
            $view->setLayoutRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['layoutRootPaths.']);
        }
        return $view;
    }

    /**
     * @return Variant
     * @throws NoSuchVariantException
     */
    protected function getConfig(): Variant
    {
        $key = self::DEFAULT_IMAGE_VARIANT_KEY;
        $variant = $this->variantFactory->get($key);

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])) {
            try {
                $key = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
                $variant = $this->variantFactory->get($key);
            } catch (NoSuchVariantException $e) {
                $key = self::DEFAULT_IMAGE_VARIANT_KEY;
                $variant = $this->variantFactory->get($key);
            }
        }

        if (!$variant instanceof PictureImageConfiguration) {
            throw new NoSuchVariantException(
                'No configuration found for key "' . $key . '". Found variant config instead. Please use ImageViewHelper or SourceViewHelper instead.',
                1624132564
            );
        }

        return $variant;
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    protected function isAnimatedGif(FileInterface $file): bool
    {
        return $this->imageService->isAnimatedGif($file);
    }
}
