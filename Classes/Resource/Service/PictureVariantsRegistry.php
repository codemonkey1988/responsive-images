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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Register class to add new image variants. Should be used in ext_localconf.php
 */
class PictureVariantsRegistry implements SingletonInterface
{
    /**
     * @var array
     */
    protected $configs = [];

    /**
     * @return PictureVariantsRegistry
     */
    public static function getInstance()
    {
        return GeneralUtility::makeInstance(PictureVariantsRegistry::class);
    }

    /**
     * @param PictureImageVariant $imageVariant
     * @return void
     */
    public function registerImageVariant(PictureImageVariant $imageVariant)
    {
        $this->configs[$imageVariant->getKey()] = $imageVariant;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function imageVariantKeyExists($key)
    {
        return isset($this->configs[$key]);
    }

    /**
     * @return PictureImageVariant[]
     */
    public function getAllImageVariants()
    {
        return $this->configs;
    }

    /**
     * @param string $key
     * @return PictureImageVariant|null
     */
    public function getImageVariant($key)
    {
        return isset($this->configs[$key]) ? $this->configs[$key] : null;
    }

    /**
     * @return void
     */
    public function removeAllImageVariants()
    {
        $this->configs = [];
    }

    /**
     * @param string $key
     * @return void
     */
    public function removeImageVariant($key)
    {
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }
}
