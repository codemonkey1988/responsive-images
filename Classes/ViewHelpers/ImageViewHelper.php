<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Service\ConfigurationService;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper as BaseImageViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

class ImageViewHelper extends BaseImageViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerTagAttribute('quality', 'int', 'Specifies the image quality for jpeg', false);
        $this->registerTagAttribute('greyscale', 'bool', 'Should be image be rendered as greyscale?', false);
    }

    /**
     * Resizes a given image (if required) and renders the respective img tag
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     *
     * @throws Exception
     * @return string Rendered tag
     */
    public function render()
    {
        $src = (string)$this->arguments['src'];
        if (($src === '' && $this->arguments['image'] === null) || ($src !== '' && $this->arguments['image'] !== null)) {
            throw new Exception('You must either specify a string src or a File object.', 1382284106);
        }

        // A URL was given as src, this is kept as is, and we can only scale
        if ($src !== '' && preg_match('/^(https?:)?\/\//', $src)) {
            $this->tag->addAttribute('src', $src);
            if (isset($this->arguments['width'])) {
                $this->tag->addAttribute('width', $this->arguments['width']);
            }
            if (isset($this->arguments['height'])) {
                $this->tag->addAttribute('height', $this->arguments['height']);
            }
        } else {
            try {
                $image = $this->imageService->getImage($src, $this->arguments['image'], (bool)$this->arguments['treatIdAsReference']);
                $cropString = $this->arguments['crop'];
                if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                    $cropString = $image->getProperty('crop');
                }
                $cropVariantCollection = CropVariantCollection::create((string)$cropString);
                $cropVariant = $this->arguments['cropVariant'] ?: 'default';
                $cropArea = $cropVariantCollection->getCropArea($cropVariant);
                $processingInstructions = [
                    'width' => $this->arguments['width'],
                    'height' => $this->arguments['height'],
                    'minWidth' => $this->arguments['minWidth'],
                    'minHeight' => $this->arguments['minHeight'],
                    'maxWidth' => $this->arguments['maxWidth'],
                    'maxHeight' => $this->arguments['maxHeight'],
                    'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
                    'additionalParameters' => $this->generateAdditionalProcessingParameters(),
                    'skipProcessing' => !$this->isProcessingEnabled(),
                ];
                if (!empty($this->arguments['fileExtension'] ?? '')) {
                    $processingInstructions['fileExtension'] = $this->arguments['fileExtension'];
                }
                $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
                $imageUri = $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);

                if (!$this->tag->hasAttribute('data-focus-area')) {
                    $focusArea = $cropVariantCollection->getFocusArea($cropVariant);
                    if (!$focusArea->isEmpty()) {
                        $this->tag->addAttribute('data-focus-area', $focusArea->makeAbsoluteBasedOnFile($image));
                    }
                }
                $this->tag->addAttribute('src', $imageUri);
                $this->tag->addAttribute('width', $processedImage->getProperty('width'));
                $this->tag->addAttribute('height', $processedImage->getProperty('height'));

                // The alt-attribute is mandatory to have valid html-code, therefore add it even if it is empty
                if (empty($this->arguments['alt'])) {
                    $this->tag->addAttribute('alt', $image->hasProperty('alternative') ? $image->getProperty('alternative') : '');
                }
                // Add title-attribute from property if not already set and the property is not an empty string
                $title = (string)($image->hasProperty('title') ? $image->getProperty('title') : '');
                if (empty($this->arguments['title']) && $title !== '') {
                    $this->tag->addAttribute('title', $title);
                }
            } catch (ResourceDoesNotExistException $e) {
                // thrown if file does not exist
                throw new Exception($e->getMessage(), 1509741911, $e);
            } catch (\UnexpectedValueException $e) {
                // thrown if a file has been replaced with a folder
                throw new Exception($e->getMessage(), 1509741912, $e);
            } catch (\RuntimeException $e) {
                // RuntimeException thrown if a file is outside of a storage
                throw new Exception($e->getMessage(), 1509741913, $e);
            } catch (\InvalidArgumentException $e) {
                // thrown if file storage does not exist
                throw new Exception($e->getMessage(), 1509741914, $e);
            }
        }
        return $this->tag->render();
    }

    protected function generateAdditionalProcessingParameters(): string
    {
        $additionalParameters = '';

        if ($this->arguments['greyscale']) {
            $additionalParameters .= ' -colorspace Gray';
        }

        if ($this->arguments['quality']) {
            $additionalParameters .= ' -quality ' . $this->arguments['quality'];
        }

        return $additionalParameters;
    }

    /**
     * @return bool
     */
    protected function isProcessingEnabled(): bool
    {
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        return $configurationService->isProcessingEnabled();
    }
}
