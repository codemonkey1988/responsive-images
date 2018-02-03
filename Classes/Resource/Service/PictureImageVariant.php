<?php
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
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $media
     * @param array  $srcsets
     * @param string $croppingVariantKey
     * @return PictureImageVariant
     */
    public function addSourceConfig($media, array $srcsets, $croppingVariantKey = 'default')
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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getAllSourceConfig()
    {
        return $this->sources;
    }

    /**
     * @return string
     */
    public function getDefaultWidth()
    {
        return $this->defaultWidth;
    }

    /**
     * @param string $defaultWidth
     * @return PictureImageVariant
     */
    public function setDefaultWidth($defaultWidth)
    {
        $this->defaultWidth = $defaultWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultHeight()
    {
        return $this->defaultHeight;
    }

    /**
     * @param string $defaultHeight
     * @return PictureImageVariant
     */
    public function setDefaultHeight($defaultHeight)
    {
        $this->defaultHeight = $defaultHeight;

        return $this;
    }
}
