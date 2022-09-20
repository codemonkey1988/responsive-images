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
 * Register class to add new image variants. Should be used in ext_localconf.php
 */
class PictureVariantsRegistry
{
    /**
     * @var PictureImageVariantFactory
     */
    protected PictureImageVariantFactory $factory;

    /**
     * @var array
     */
    protected array $configs = [];

    /**
     * PictureVariantsRegistry constructor.
     * @param PictureImageVariantFactory $factory
     */
    public function __construct(PictureImageVariantFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param PictureImageVariant $imageVariant
     */
    public function registerImageVariant(PictureImageVariant $imageVariant): void
    {
        $this->configs[$imageVariant->getKey()] = $imageVariant;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function imageVariantKeyExists(string $key): bool
    {
        try {
            $variant = $this->getImageVariant($key);
            return $variant instanceof PictureImageVariant;
        } catch (NoSuchVariantException $e) {
            return false;
        }
    }

    /**
     * @param string $key
     * @return PictureImageVariant
     * @throws NoSuchVariantException
     */
    public function getImageVariant(string $key): PictureImageVariant
    {
        if (!isset($this->configs[$key])) {
            $this->configs[$key] = $this->factory->get($key);
        }

        return $this->configs[$key];
    }

    public function removeAllImageVariants(): void
    {
        $this->configs = [];
    }

    /**
     * @param string $key
     */
    public function removeImageVariant(string $key): void
    {
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }
}
