<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Service;

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
    protected array $configs = [];

    public function __construct()
    {
        $this->initializeTypoScriptConfiguration();
    }

    /**
     * @return PictureVariantsRegistry
     */
    public static function getInstance(): self
    {
        return GeneralUtility::makeInstance(self::class);
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
     * @return PictureImageVariant
     * @throws NoSuchVariantException
     */
    public function getImageVariant(string $key): PictureImageVariant
    {
        if ($this->imageVariantKeyExists($key)) {
            return $this->configs[$key];
        }

        throw new NoSuchVariantException('No variant found with key "' . $key . '".', 1623538021);
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

    protected function initializeTypoScriptConfiguration(): void
    {
        $plainConfig = [];

        if (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['configuration.'])) {
            $plainConfig = GeneralUtility::removeDotsFromTS($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['configuration.']);
        }

        foreach ($plainConfig as $key => $imageVariantConfig) {
            $this->configs[$key] = GeneralUtility::makeInstance(PictureImageVariant::class, $key, $imageVariantConfig);
        }
    }
}
