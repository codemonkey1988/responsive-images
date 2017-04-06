<?php

namespace Codemonkey1988\ResponsiveImages\Resource\Rendering;

/***************************************************************
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class PictureTagRenderer
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Resource\Rendering
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class PictureTagRenderer implements FileRendererInterface
{
    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';

    /**
     * @var ImageService
     */
    static $imageService;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $possibleMimeTypes = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');

    /**
     * @var string
     */
    protected $additionalOptions = '';

    /**
     * @var boolean
     */
    protected $usePicutreTag = true;

    /**
     * @var string
     */
    protected $cssClass;

    /**
     * @return int
     */
    public function getPriority()
    {
        return 5;
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function canRender(FileInterface $file)
    {
        /** @var EnvironmentService $evironmentService */
        $evironmentService = GeneralUtility::makeInstance(EnvironmentService::class);
        $registry          = PictureVariantsRegistry::getInstance();

        return $evironmentService->isEnvironmentInFrontendMode()
            && $registry->imageVariantKeyExists(self::DEFAULT_IMAGE_VARIANT_KEY)
            && in_array($file->getMimeType(), $this->possibleMimeTypes, true);
    }

    /**
     * @param FileInterface $file
     * @param int|string    $width
     * @param int|string    $height
     * @param array         $options
     * @param bool          $usedPathsRelativeToCurrentScript
     * @return string
     */
    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = array(),
        $usedPathsRelativeToCurrentScript = false
    ) {

        $imageVarientConfigKey = self::DEFAULT_IMAGE_VARIANT_KEY;
        $registry              = PictureVariantsRegistry::getInstance();

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY]) && $registry->imageVariantKeyExists($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])) {
            $imageVarientConfigKey = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
        }

        // Parse the additional options
        $this->setOptions($options);
        $imageString = $this->processImageWithConfig($imageVarientConfigKey, $file, $options);
        $this->resetOptions();

        return $imageString;
    }

    /**
     * @param array $options
     * @return void
     */
    protected function setOptions(array $options)
    {
        if (isset($options['grayscale']) && $options['grayscale'] == true) {
            $this->additionalOptions .= ' -colorspace Gray';
        }

        if (isset($options['disablePictureTag']) && $options['disablePictureTag'] == true) {
            $this->usePicutreTag = false;
        }

        if (isset($options['class']) && $options['class']) {
            $this->cssClass = $options['class'];
        }
    }

    /**
     * @param string        $key
     * @param FileInterface $file
     * @param array         $options
     * @return string
     */
    protected function processImageWithConfig($key, FileInterface $file, array $options)
    {
        $sources  = array();
        $registry = PictureVariantsRegistry::getInstance();

        if (!$registry->imageVariantKeyExists($key)) {
            $key = self::DEFAULT_IMAGE_VARIANT_KEY;
        }

        $imageVariantConfig = $registry->getImageVariant($key);

        if ($this->usePicutreTag) {
            foreach ($imageVariantConfig->getAllSourceConfig() as $sourceConfig) {
                $sources[] = $this->generateSource($file, $sourceConfig);
            }
        }

        if ($sources) {
            return $this->buildPictureTag($sources, $file, $imageVariantConfig);
        } else {
            return $this->renderDefaultImage($file, $options['width'], $options['height']);
        }
    }

    /**
     * @param FileInterface $file
     * @param array         $config
     * @return string
     */
    protected function generateSource(FileInterface $file, $config)
    {
        $media   = '';
        $srcsets = array();
        $crop    = $file->getProperty('crop');

        if (!is_array($config['srcset']) || !$config['srcset']) {
            return '';
        }

        if (isset($config['media']) && $config['media']) {
            $media = $config['media'];
        }

        foreach ($config['srcset'] as $density => $srcstConfig) {
            $processingConfig         = $srcstConfig;
            $processingConfig['crop'] = $crop;

            if (isset($srcstConfig['quality'])) {
                if (is_numeric($srcstConfig['quality'])) {
                    $processingConfig['additionalParameters'] = '-quality ' . (int)$srcstConfig['quality'] . ' ' . $this->additionalOptions;
                }

                unset($srcstConfig['quality']);
            }

            $imageService   = $this->getImageService();
            $processedImage = $imageService->applyProcessingInstructions($file, $processingConfig);
            $imageUri       = $imageService->getImageUri($processedImage);

            $srcsets[] = $imageUri . ' ' . $density;
        }

        return sprintf('<source media="%s" srcset="%s" />', $media, implode(',', $srcsets));
    }

    /**
     * Return an instance of ImageService
     *
     * @return ImageService
     */
    protected function getImageService()
    {
        if (!static::$imageService) {
            $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
            $environmentservice   = GeneralUtility::makeInstance(EnvironmentService::class);
            $resourceFactory      = GeneralUtility::makeInstance(ResourceFactory::class, $signalSlotDispatcher);

            /** @var ImageService $imageService */
            static::$imageService = GeneralUtility::makeInstance(ImageService::class);
            static::$imageService->injectEnvironmentService($environmentservice);
            static::$imageService->injectResourceFactory($resourceFactory);
        }

        return static::$imageService;
    }

    /**
     * @param array               $sources
     * @param FileInterface       $file
     * @param PictureImageVariant $imageVariantConfig
     * @return string
     */
    protected function buildPictureTag($sources, $file, $imageVariantConfig)
    {
        $br = "\n";

        return "<picture>" . $br . implode($br, $sources) . $br . $this->renderDefaultImage($file,
                $imageVariantConfig->getDefaultWidth(), $imageVariantConfig->getDefaultHeight()) . $br . "</picture>";
    }

    /**
     * Render img tag
     *
     * @param FileInterface $image
     * @param string        $width
     * @param string        $height
     * @return string Rendered img tag
     */
    protected function renderDefaultImage(FileInterface $image, $width, $height)
    {
        $additionalAttributes = array();
        $imageService         = $this->getImageService();
        $crop                 = $image instanceof FileReference ? $image->getProperty('crop') : null;

        $processingInstructions = array(
            'width'                => $width,
            'height'               => $height,
            'crop'                 => $crop,
            'additionalParameters' => $this->additionalOptions,
        );

        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
        $imageUri       = $imageService->getImageUri($processedImage);

        $alt   = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        if ($alt) {
            $additionalAttributes[] = sprintf('alt="%s"', $alt);
        }
        if ($title) {
            $additionalAttributes[] = sprintf('title="%s"', $title);
        }
        if ($this->cssClass) {
            $additionalAttributes[] = sprintf('class="%s"', $this->cssClass);
        }

        return sprintf('<img src="%s" width="%s" height="%s" %s/>',
            $imageUri,
            $processedImage->getProperty('width'),
            $processedImage->getProperty('height'),
            implode(' ', $additionalAttributes)
        );
    }

    /**
     * @return void
     */
    protected function resetOptions()
    {
        $this->additionalOptions = '';
        $this->usePicutreTag     = true;
    }
}