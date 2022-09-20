<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Rendering;

use Codemonkey1988\ResponsiveImages\Event\AfterSrcsetProcessingEvent;
use Codemonkey1988\ResponsiveImages\Event\BeforeSrcsetProcessingEvent;
use Codemonkey1988\ResponsiveImages\Exception;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Imaging\ImageManipulation\Area;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * @phpstan-type TProcessingInstructions array{
 *     width: string|int|null,
 *     height: string|int|null,
 *     minWidth: string|int|null,
 *     minHeight: string|int|null,
 *     maxWidth: string|int|null,
 *     maxHeight: string|int|null,
 *     crop: Area|null,
 *     additionalParameters?: string
 * }
 */
class AttributeRenderer
{
    protected ImageService $imageService;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(ImageService $imageService, EventDispatcherInterface $eventDispatcher)
    {
        $this->imageService = $imageService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws Exception
     */
    public function renderSrcset(FileInterface $image, Variant $variant, string $cropVariant = 'default'): string
    {
        $allProcessingInstructions = $this->buildProcessingInstructions($image, $variant, $cropVariant);
        $srcset = [];

        $event = new BeforeSrcsetProcessingEvent($allProcessingInstructions, $image, $variant, $cropVariant);
        $this->eventDispatcher->dispatch($event);
        $allProcessingInstructions = $event->getProcessingInstructions();

        foreach ($allProcessingInstructions as $key => $processingInstructions) {
            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            $imageUri = $this->imageService->getImageUri($processedImage, (bool)($variant->getConfig()['absolute'] ?? false));
            $prefix = $processedImage->getProperty('width') . 'w';
            if (isset($variant->getConfig()['srcset.'][$key . '.']['prefix'])) {
                $prefix = $variant->getConfig()['srcset.'][$key . '.']['prefix'];
            }
            $srcset[$key] = sprintf('%s %s', $imageUri, $prefix);
        }

        $event = new AfterSrcsetProcessingEvent($srcset, $image, $variant);
        $this->eventDispatcher->dispatch($event);
        $srcset = $event->getSrcset();

        return implode(', ', $srcset);
    }

    /**
     * @throws Exception
     */
    public function renderSizes(Variant $variant): string
    {
        $sizes = [];

        foreach ($variant->getConfig()['sizes.'] ?? []  as $key => $size) {
            $key = rtrim($key, '.');
            if (!MathUtility::canBeInterpretedAsInteger($key)) {
                throw new Exception('Keys for variant sizes configuration needs to be numeric', 1624200902);
            }
            if (!isset($size['assumedImageWidth']) || strlen($size['assumedImageWidth']) === 0) {
                continue;
            }
            if (isset($size['viewportMediaCondition']) && strlen($size['viewportMediaCondition']) > 0) {
                $sizes[$key] = sprintf(
                    '%s %s',
                    $size['viewportMediaCondition'],
                    $size['assumedImageWidth']
                );
            } else {
                $sizes[$key] = $size['assumedImageWidth'];
            }
        }
        ksort($sizes);

        return implode(', ', $sizes);
    }

    /**
     * @return array<string, TProcessingInstructions>
     * @throws Exception
     */
    protected function buildProcessingInstructions(FileInterface $image, Variant $variant, string $cropVariant): array
    {
        $processingInstructions = [];

        foreach ($variant->getConfig()['providedImageSizes.'] ?? [] as $key => $srcset) {
            $key = rtrim($key, '.');
            if (!MathUtility::canBeInterpretedAsInteger($key)) {
                throw new Exception('Keys for variant srcset configuration needs to be numeric', 1624200902);
            }
            $cropString = null;
            if ($image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }
            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);

            $processingInstructions[$key] = [
                'width' => $srcset['width'] ?? null,
                'height' => $srcset['height'] ?? null,
                'minWidth' => $srcset['minWidth'] ?? null,
                'minHeight' => $srcset['minHeight'] ?? null,
                'maxWidth' => $srcset['maxWidth'] ?? null,
                'maxHeight' => $srcset['maxHeight'] ?? null,
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
        }
        ksort($processingInstructions);

        return $processingInstructions;
    }
}
