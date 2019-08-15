<?php
declare(strict_types=1);
namespace Codemonkey1988\ResponsiveImages\Resource\Service;

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

/**
 * Class to store configuration for different picture tag configurations.
 */
class PictureImageVariant
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var array
     */
    protected $sources = [];
    /**
     * @var string
     */
    protected $layoutKey = '';
    /**
     * @var string
     */
    protected $defaultWidth = '1920';
    /**
     * @var string
     */
    protected $defaultHeight = '';

    /**
     * PictureImageVariant constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param string $media
     * @param array  $srcsets
     * @param string $croppingVariantKey
     * @return PictureImageVariant
     */
    public function addSourceConfig(string $media, array $srcsets, string $croppingVariantKey = 'default'): self
    {
        $this->sources[] = [
            'media' => $media,
            'srcset' => $srcsets,
            'croppingVariantKey' => $croppingVariantKey,
        ];

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getAllSourceConfig(): array
    {
        return $this->sources;
    }

    /**
     * @return string
     */
    public function getLayoutKey(): string
    {
        return $this->layoutKey;
    }

    /**
     * @param string $layoutKey
     * @return PictureImageVariant
     */
    public function setLayoutKey(string $layoutKey): self
    {
        $this->layoutKey = $layoutKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultWidth(): string
    {
        return $this->defaultWidth;
    }

    /**
     * @param string $defaultWidth
     * @return PictureImageVariant
     */
    public function setDefaultWidth(string $defaultWidth): self
    {
        $this->defaultWidth = $defaultWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultHeight(): string
    {
        return $this->defaultHeight;
    }

    /**
     * @param string $defaultHeight
     * @return PictureImageVariant
     */
    public function setDefaultHeight(string $defaultHeight): self
    {
        $this->defaultHeight = $defaultHeight;

        return $this;
    }
}
