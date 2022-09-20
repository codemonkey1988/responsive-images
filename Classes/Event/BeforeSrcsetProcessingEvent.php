<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Event;

use Codemonkey1988\ResponsiveImages\Rendering\AttributeRenderer;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Psr\EventDispatcher\StoppableEventInterface;
use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * @phpstan-import-type TProcessingInstructions from AttributeRenderer
 */
final class BeforeSrcsetProcessingEvent implements StoppableEventInterface
{
    /**
     * @var array<string, TProcessingInstructions>
     */
    protected array $processingInstructions;

    protected FileInterface $image;

    protected Variant $variant;

    protected string $cropVariant;

    private bool $stopRendering = false;

    /**
     * @param array<string, TProcessingInstructions> $processingInstructions
     */
    public function __construct(
        array $processingInstructions,
        FileInterface $image,
        Variant $variant,
        string $cropVariant
    ) {
        $this->processingInstructions = $processingInstructions;
        $this->image = $image;
        $this->variant = $variant;
        $this->cropVariant = $cropVariant;
    }

    /**
     * @param array<string, TProcessingInstructions> $processingInstructions
     */
    public function setProcessingInstructions(array $processingInstructions): void
    {
        $this->processingInstructions = $processingInstructions;
    }

    /**
     * @return array<string, TProcessingInstructions>
     */
    public function getProcessingInstructions(): array
    {
        return $this->processingInstructions;
    }

    public function getImage(): FileInterface
    {
        return $this->image;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function getCropVariant(): string
    {
        return $this->cropVariant;
    }

    /**
     * Prevent other listeners from being called if rendering is stopped by listener.
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopRendering;
    }

    public function setStopRendering(bool $stopRendering): void
    {
        $this->stopRendering = $stopRendering;
    }
}
