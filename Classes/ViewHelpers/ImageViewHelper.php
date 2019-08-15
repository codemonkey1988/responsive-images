<?php
declare(strict_types=1);
namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
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
        $this->registerTagAttribute('grayscale', 'bool', 'Should be image be rendered as greyscale?', false);
        $this->registerArgument('layoutKey', 'string', 'Specifies the tag layout', false, '');
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
        if (($this->arguments['src'] === null && $this->arguments['image'] === null) || ($this->arguments['src'] !== null && $this->arguments['image'] !== null)) {
            throw new Exception('You must either specify a string src or a File object.', 1382284106);
        }

        try {
            $image = $this->imageService->getImage($this->arguments['src'], $this->arguments['image'], $this->arguments['treatIdAsReference']);
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
                // Added by me
                'additionalParameters' => $this->generateAdditionalProcessingParameters(),
                'skipProcessing' => !ConfigurationUtility::isProcessingEnabled(),
            ];
            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            $imageUri = $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);

            if (!$this->tag->hasAttribute('data-focus-area')) {
                $focusArea = $cropVariantCollection->getFocusArea($cropVariant);
                if (!$focusArea->isEmpty()) {
                    $this->tag->addAttribute('data-focus-area', $focusArea->makeAbsoluteBasedOnFile($image));
                }
            }

            $layoutKey = $this->arguments['layoutKey'];
            switch ($layoutKey) {
                case 'data-srcset':
                    // Image placeholder: empty SVG with correct aspect ratio.
                    // Use huge SVG as Internet Explorer does no up-scaling for SVGs.
                    $width = 2048;
                    $height = (int)($processedImage->getProperty('height') / $processedImage->getProperty('width') * $width);
                    $this->tag->addAttribute(
                        'src',
                        "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%20" .
                        $width . "%20" . $height .
                        "'%2F%3E"
                    );
                    $this->tag->addAttribute('data-src', $imageUri);
                    $this->tag->addAttribute('width', $width);
                    $this->tag->addAttribute('height', $height);
                    break;
                case 'srcset':
                default:
                    $this->tag->addAttribute('src', $imageUri);
                    $this->tag->addAttribute('width', $processedImage->getProperty('width'));
                    $this->tag->addAttribute('height', $processedImage->getProperty('height'));
                    break;
            }

            $alt = $image->getProperty('alternative');
            $title = $image->getProperty('title');

            // The alt-attribute is mandatory to have valid html-code, therefore add it even if it is empty
            if (empty($this->arguments['alt'])) {
                $this->tag->addAttribute('alt', $alt);
            }
            if (empty($this->arguments['title']) && $title) {
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

        return $this->tag->render();
    }

    protected function generateAdditionalProcessingParameters(): string
    {
        $additionalParameters = '';

        if ($this->arguments['grayscale']) {
            trigger_error(
                'Option grayscale will be removed soon. Please use greyscale instead',
                E_USER_DEPRECATED
            );

            $additionalParameters .= ' -colorspace Gray';
        } elseif ($this->arguments['greyscale']) {
            $additionalParameters .= ' -colorspace Gray';
        }

        if ($this->arguments['quality']) {
            $additionalParameters .= ' -quality ' . $this->arguments['quality'];
        }

        return $additionalParameters;
    }
}
