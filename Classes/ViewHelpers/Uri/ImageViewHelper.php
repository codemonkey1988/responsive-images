<?php
declare(strict_types=1);
namespace Codemonkey1988\ResponsiveImages\ViewHelpers\Uri;

use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper as BaseImageViewHelper;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;


class ImageViewHelper extends BaseImageViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('quality', 'int', 'Specifies the image quality for jpeg', false);
        $this->registerArgument('greyscale', 'bool', 'Should be image be rendered as greyscale?', false, false);
        $this->registerArgument('grayscale', 'bool', 'Should be image be rendered as grayscale? (deprecated)', false, false);
    }

    /**
     * Resizes the image (if required) and returns its path. If the image was not resized, the path will be equal to $src
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws Exception
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $src = $arguments['src'];
        $image = $arguments['image'];
        $treatIdAsReference = $arguments['treatIdAsReference'];
        $cropString = $arguments['crop'];
        $absolute = $arguments['absolute'];

        if (($src === null && $image === null) || ($src !== null && $image !== null)) {
            throw new Exception('You must either specify a string src or a File object.', 1460976233);
        }

        try {
            $imageService = self::getImageService();
            $image = $imageService->getImage($src, $image, $treatIdAsReference);

            if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }

            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $arguments['width'],
                'height' => $arguments['height'],
                'minWidth' => $arguments['minWidth'],
                'minHeight' => $arguments['minHeight'],
                'maxWidth' => $arguments['maxWidth'],
                'maxHeight' => $arguments['maxHeight'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
                // Added by me
                'additionalParameters' => self::generateAdditionalProcessingParameters($arguments),
                'skipProcessing' => !ConfigurationUtility::isProcessingEnabled(),
            ];

            $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
            return $imageService->getImageUri($processedImage, $absolute);
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new Exception($e->getMessage(), 1509741907, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new Exception($e->getMessage(), 1509741908, $e);
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
            throw new Exception($e->getMessage(), 1509741909, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new Exception($e->getMessage(), 1509741910, $e);
        }
    }

    /**
     * @param array $arguments
     * @return string
     */
    static protected function generateAdditionalProcessingParameters(array $arguments): string
    {
        $additionalParameters = '';

        if ($arguments['grayscale']) {
            trigger_error(
                'Option grayscale will be removed soon. Please use greyscale instead',
                E_USER_DEPRECATED
            );

            $additionalParameters .= ' -colorspace Gray';
        } elseif ($arguments['greyscale']) {
            $additionalParameters .= ' -colorspace Gray';
        }

        if ($arguments['quality']) {
            $additionalParameters .= ' -quality ' . $arguments['quality'];
        }

        return $additionalParameters;
    }
}
