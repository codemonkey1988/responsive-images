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

final class AfterSrcsetProcessingEvent implements StoppableEventInterface
{
    /**
     * @var array
     */
    protected array $srcset;

    /**
     * @var FileInterface
     */
    protected FileInterface $image;

    /**
     * @var Variant
     */
    protected Variant $variant;

    /**
     * @var bool
     */
    private bool $stopRendering = false;

    /**
     * @param array $srcset
     * @param FileInterface $image
     * @param Variant $variant
     */
    public function __construct(
        array $srcset,
        FileInterface $image,
        Variant $variant
    ) {
        $this->srcset = $srcset;
        $this->image = $image;
        $this->variant = $variant;
    }

    /**
     * @param array $srcset
     */
    public function setSrcset(array $srcset): void
    {
        $this->srcset = $srcset;
    }

    /**
     * @return array
     */
    public function getSrcset(): array
    {
        return $this->srcset;
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
