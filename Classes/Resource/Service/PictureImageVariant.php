<?php

namespace Codemonkey1988\ResponsiveImages\Resource\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class PictureVariantsRegistry
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Resource\Rendering
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
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
    protected $sources = array();

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
     * @return PictureImageVariant
     */
    public function addSourceConfig($media, array $srcsets)
    {
        $this->sources[] = array(
            'media'  => $media,
            'srcset' => $srcsets,
        );

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