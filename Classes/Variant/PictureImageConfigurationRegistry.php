<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Variant;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @deprecated Please use variants and register them using TypoScript.
 */
class PictureImageConfigurationRegistry
{
    /**
     * @var VariantFactory
     */
    protected VariantFactory $variantFactory;

    /**
     * @param VariantFactory $variantFactory
     */
    public function __construct(VariantFactory $variantFactory)
    {
        trigger_error(
            'Usage of registry is deprecated and will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        $this->variantFactory = $variantFactory;
    }

    /**
     * @return PictureImageConfigurationRegistry
     */
    public static function getInstance(): self
    {
        return GeneralUtility::makeInstance(self::class);
    }

    /**
     * @param PictureImageConfiguration $imageVariant
     */
    public function registerImageVariant(PictureImageConfiguration $imageVariant)
    {
        $this->variantFactory->addConfiguration($imageVariant);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function imageVariantKeyExists(string $key): bool
    {
        return $this->variantFactory->hasConfiguration($key);
    }

    /**
     * @return array
     */
    public function getAllImageVariants(): array
    {
        return $this->variantFactory->getAllConfiguration();
    }

    /**
     * @param string $key
     * @return PictureImageConfiguration|null
     */
    public function getImageVariant($key)
    {
        return $this->variantFactory->getConfiguration($key);
    }

    public function removeAllImageVariants()
    {
        $this->variantFactory->truncateConfiguration();
    }

    /**
     * @param string $key
     */
    public function removeImageVariant(string $key)
    {
        $this->variantFactory->removeConfiguration($key);
    }
}
