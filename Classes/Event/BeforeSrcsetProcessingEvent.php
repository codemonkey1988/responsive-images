<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Event;

use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Psr\EventDispatcher\StoppableEventInterface;
use TYPO3\CMS\Core\Resource\FileInterface;

final class BeforeSrcsetProcessingEvent implements StoppableEventInterface
{
    /**
     * @var array
     */
    protected array $processingInstructions;

    /**
     * @var FileInterface
     */
    protected FileInterface $image;

    /**
     * @var Variant
     */
    protected Variant $variant;

    /**
     * @var string
     */
    protected string $cropVariant;

    /**
     * @var bool
     */
    private bool $stopRendering = false;

    /**
     * @param array $processingInstructions
     * @param FileInterface $image
     * @param Variant $variant
     * @param string $cropVariant
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
     * @param array $processingInstructions
     */
    public function setProcessingInstructions(array $processingInstructions): void
    {
        $this->processingInstructions = $processingInstructions;
    }

    /**
     * @return array
     */
    public function getProcessingInstructions(): array
    {
        return $this->processingInstructions;
    }

    /**
     * @return FileInterface
     */
    public function getImage(): FileInterface
    {
        return $this->image;
    }

    /**
     * @return Variant
     */
    public function getVariant(): Variant
    {
        return $this->variant;
    }

    /**
     * @return string
     */
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

    /**
     * @param bool $stopRendering
     */
    public function setStopRendering(bool $stopRendering): void
    {
        $this->stopRendering = $stopRendering;
    }
}
