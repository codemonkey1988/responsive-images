<?php
declare(strict_types=1);
namespace Codemonkey1988\ResponsiveImages\Resource\Rendering;

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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\ImgTagRenderer;
use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\PictureTagRenderer;
use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\SourceTagRenderer;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureVariantsRegistry;
use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * Class to render a picture tag with different sources and a fallback image.
 */
class ResponsiveImageRenderer implements FileRendererInterface
{
    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';
    const REGISTER_IMAGE_RELATVE_WIDTH_KEY = 'IMAGE_RELATIVE_WIDTH_KEY';
    const OPTIONS_IMAGE_RELATVE_WIDTH_KEY = 'relativeScalingWidth';

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var array
     */
    protected $possibleMimeTypes = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];

    /**
     * @var bool
     */
    protected $isAnimatedGif = false;

    /**
     * @var \Codemonkey1988\ResponsiveImages\Resource\Service\ImageService
     */
    protected $imageService;

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
        /** @var EnvironmentService $evironmentService */
        $evironmentService = GeneralUtility::makeInstance(EnvironmentService::class);
        $registry = PictureVariantsRegistry::getInstance();

        $enabled = ConfigurationUtility::isEnabled();

        return $enabled && $evironmentService->isEnvironmentInFrontendMode()
            && $registry->imageVariantKeyExists(self::DEFAULT_IMAGE_VARIANT_KEY)
            && in_array($file->getMimeType(), $this->possibleMimeTypes, true);
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
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->isAnimatedGif = $this->isAnimatedGif($file);

        if (!array_key_exists(self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY, $options)
            && isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATVE_WIDTH_KEY])
        ) {
            $options[self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY] = (float)$GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATVE_WIDTH_KEY];
        }

        // Check if a responsive image tag should be rendered. If not, just return the normal image tag.
        if ($this->isAnimatedGif || (isset($options['disablePictureTag']) && $options['disablePictureTag'] == true)) {
            return $this->generateImgTag($file, (string)$width, (string)$height, $options);
        }
        return $this->generatePictureTag($file, (string)$width, (string)$height, $options);
    }

    /**
     * Generates a normal img-tag.
     *
     * @param FileInterface $file
     * @param string $width
     * @param string $height
     * @param array $options
     * @return string
     */
    protected function generateImgTag(FileInterface $file, string $width, string $height, array $options = [])
    {
        $allowedAdditionalAttributes = ['alt', 'title', 'class', 'id', 'lang', 'style', 'accesskey', 'tabindex', 'onclick'];
        $additionalParameters = '';

        if (isset($options['grayscale']) && $options['grayscale'] == true) {
            $additionalParameters .= ' -colorspace Gray';
        }

        $relativeScalingWidth = array_key_exists(self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY, $options) ? $options[self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY] : 0;

        $processedImage = $this->processImage($file, $width, $height, 'default', $relativeScalingWidth, $additionalParameters);

        /** @var ImgTagRenderer $tagRenderer */
        $tagRenderer = $this->objectManager->get(ImgTagRenderer::class);
        $tagRenderer->initialize();

        $tagRenderer->addAttribute('width', (string)$processedImage->getProperty('width'));
        $tagRenderer->addAttribute('height', (string)$processedImage->getProperty('height'));
        $tagRenderer->addAttribute('src', $this->getImageUri($processedImage));

        if ($file->getProperty('alternative')) {
            $tagRenderer->addAttribute('alt', $file->getProperty('alternative'));
        }
        if ($file->getProperty('title')) {
            $tagRenderer->addAttribute('title', $file->getProperty('title'));
        }

        if (isset($options['additionalAttributes']) && is_array($options['additionalAttributes'])) {
            foreach ($options['additionalAttributes'] as $attrName => $attrValue) {
                if ($attrValue) {
                    $tagRenderer->addAttribute($attrName, $attrValue);
                }
            }
        }

        if (isset($options['data']) && is_array($options['data'])) {
            foreach ($options['data'] as $attrName => $attrValue) {
                if ($attrValue) {
                    $tagRenderer->addAttribute('data-' . $attrName, $attrValue);
                }
            }
        }

        foreach ($options as $attrName => $attrValue) {
            if (in_array($attrName, $allowedAdditionalAttributes) && $attrValue) {
                $tagRenderer->addAttribute($attrName, $attrValue);
            }
        }

        return $tagRenderer->render();
    }

    /**
     * Processes an image.
     *
     * @param FileInterface $file
     * @param string $width
     * @param string $height
     * @param string $croppingVariantKey
     * @param float $relativeScalingWidth
     * @param string $additionalParameters
     * @return FileInterface
     * @internal param int $relativeScaling
     */
    protected function processImage(FileInterface $file, string $width, string $height, string $croppingVariantKey = 'default', float $relativeScalingWidth = 0.0, string $additionalParameters = ''): FileInterface
    {
        if (!$this->isAnimatedGif) {
            $imageService = $this->getImageService();
            $processingInstructions = [
                'width' => $width,
                'height' => $height,
                'additionalParameters' => $additionalParameters,
                'skipProcessing' => !ConfigurationUtility::isProcessingEnabled(),
            ];

            if (class_exists('TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection')) {
                $cropString = $file instanceof FileReference ? $file->getProperty('crop') : '';
                $cropVariantCollection = CropVariantCollection::create((string)$cropString);
                $cropArea = $cropVariantCollection->getCropArea($croppingVariantKey);
                $processingInstructions['crop'] = $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($file);
            } else {
                $processingInstructions['crop'] = $file instanceof FileReference ? $file->getProperty('crop') : null;
            }

            $processedImage = $imageService->applyProcessingInstructions($file, $processingInstructions);

            if ($relativeScalingWidth > 0) {
                $relativeScalingProcessingInstructions = [
                    'crop' => false,
                    'width' => $processedImage->getProperty('width') * $relativeScalingWidth,
                ];

                $scaleProcessedImage = $imageService->applyProcessingInstructions($processedImage, $relativeScalingProcessingInstructions);
                $processedImage->delete(true);
                return $scaleProcessedImage;
            }
        } else {
            $processedImage = $file;
        }

        return $processedImage;
    }

    /**
     * Return an instance of ImageService
     *
     * @return ImageService
     */
    protected function getImageService(): ImageService
    {
        return $this->objectManager->get(ImageService::class);
    }

    /**
     * @param FileInterface $file
     * @return string
     */
    protected function getImageUri(FileInterface $file): string
    {
        $imageService = $this->getImageService();

        return $imageService->getImageUri($file);
    }

    /**
     * Generate a picture-tag width different sources and a fallback img-tag.
     *
     * @param FileInterface $file
     * @param string $width
     * @param string $height
     * @param array $options
     * @return string
     */
    protected function generatePictureTag(FileInterface $file, string $width, string $height, array $options = []): string
    {
        /** @var ResponsiveImageRenderer $pictureTagRenderer */
        $pictureTagRenderer = $this->objectManager->get(PictureTagRenderer::class);
        $imageVarientConfigKey = self::DEFAULT_IMAGE_VARIANT_KEY;
        $registry = PictureVariantsRegistry::getInstance();
        $sources = [];

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])
            && $registry->imageVariantKeyExists(
                $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY]
            )
        ) {
            $imageVarientConfigKey = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
        }

        $imageVariantConfig = $registry->getImageVariant($imageVarientConfigKey);
        $fallbackImage = $this->generateImgTag(
            $file,
            $imageVariantConfig->getDefaultWidth(),
            $imageVariantConfig->getDefaultHeight(),
            $options
        );

        foreach ($imageVariantConfig->getAllSourceConfig() as $sourceConfig) {
            $sources[] = $this->generateSource($file, $sourceConfig, $options);
        }

        if ($sources) {
            return $pictureTagRenderer->render(implode('', $sources) . $fallbackImage);
        }
        return $fallbackImage;
    }

    /**
     * @param FileInterface $file
     * @param array $config
     * @param array $options
     * @return string
     */
    protected function generateSource(FileInterface $file, array $config, array $options = []): string
    {
        $srcsets = [];
        $sourceTagRenderer = $this->objectManager->get(SourceTagRenderer::class);
        $additionalParameters = '';
        $croppingVariantKey = (!empty($config['croppingVariantKey'])) ? $config['croppingVariantKey'] : 'default';

        if (isset($options['grayscale']) && $options['grayscale'] == true) {
            $additionalParameters .= ' -colorspace Gray';
        }

        if (!is_array($config['srcset']) || !$config['srcset']) {
            return '';
        }

        if (isset($config['media']) && $config['media']) {
            $sourceTagRenderer->addAttribute('media', $config['media']);
        }

        $relativeScalingWidth = array_key_exists(self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY, $options) ? $options[self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY] : 0;

        foreach ($config['srcset'] as $density => $srcstConfig) {
            $width = ($srcstConfig['width']) ?: '';
            $height = ($srcstConfig['height']) ?: '';

            if (isset($srcstConfig['quality']) && is_numeric($srcstConfig['quality'])) {
                $additionalParameters .= ' -quality ' . intval($srcstConfig['quality']);
            }

            $processedImage = $this->processImage($file, (string)$width, (string)$height, $croppingVariantKey, $relativeScalingWidth, $additionalParameters);

            $srcsets[] = $this->getImageUri($processedImage) . ' ' . $density;
        }

        $sourceTagRenderer->addAttribute('srcset', implode(',', $srcsets));

        return $sourceTagRenderer->render();
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