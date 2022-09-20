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
     * @var array<string, string>
     */
    protected array $srcset;

    protected FileInterface $image;

    protected Variant $variant;

    private bool $stopRendering = false;

    /**
     * @param array<string, string> $srcset
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
     * @param array<string, string> $srcset
     */
    public function setSrcset(array $srcset): void
    {
        $this->srcset = $srcset;
    }

    /**
     * @return array<string, string>
     */
    public function getSrcset(): array
    {
        return $this->srcset;
    }

    public function getImage(): FileInterface
    {
        return $this->image;
    }

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

    public function setStopRendering(bool $stopRendering): void
    {
        $this->stopRendering = $stopRendering;
    }
}
