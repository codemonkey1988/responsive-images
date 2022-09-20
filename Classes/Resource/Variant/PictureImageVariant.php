<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Variant;

/**
 * Class to store configuration for different picture tag configurations.
 */
class PictureImageVariant
{
    /**
     * @var string
     */
    protected string $key;
    /**
     * @var array
     */
    protected array $sources = [];
    /**
     * @var string
     */
    protected string $defaultWidth = '1920';
    /**
     * @var string
     */
    protected string $defaultHeight = '';

    /**
     * @var array
     */
    protected array $mimeTypes = [];

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

    /**
     * @return array
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    /**
     * @param array $mimeTypes
     * @return PictureImageVariant
     */
    public function setMimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }
}
