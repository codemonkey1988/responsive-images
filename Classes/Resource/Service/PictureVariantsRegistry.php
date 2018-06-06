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
    public static function getInstance(): self
    {
        return GeneralUtility::makeInstance(self::class);
    }

    /**
     * @param PictureImageVariant $imageVariant
     * @return void
     * @deprecated
     */
    public function registerImageVariant(PictureImageVariant $imageVariant): void
    {
        GeneralUtility::deprecationLog('Registering an image variant by PictureVariantsRegistry::registerImageVariant is deprecated and will be removed in 3.0. Please use TypoScript configuration instead.');

        $this->configs[$imageVariant->getKey()] = $imageVariant;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function imageVariantKeyExists($key): bool
    {
        return isset($this->configs[$key]);
    }

    /**
     * @return array
     */
    public function getAllImageVariants(): array
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
     * @deprecated 
     */
    public function removeAllImageVariants(): void
    {
        GeneralUtility::deprecationLog('Removing all image variants by PictureVariantsRegistry::removeAllImageVariants is deprecated and will be removed in 3.0.');

        $this->configs = [];
    }

    /**
     * @param string $key
     * @return void
     * @deprecated
     */
    public function removeImageVariant(string $key): void
    {
        GeneralUtility::deprecationLog('Removing an image variant by PictureVariantsRegistry::removeImageVariant is deprecated and will be removed in 3.0.');
        
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }
}
