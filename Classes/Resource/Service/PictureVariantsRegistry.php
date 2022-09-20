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
    protected $configs = [];

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
    public function registerImageVariant(PictureImageVariant $imageVariant)
    {
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

    public function removeAllImageVariants()
    {
        $this->configs = [];
    }

    /**
     * @param string $key
     */
    public function removeImageVariant(string $key)
    {
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }

    protected function initializeTypoScriptConfiguration()
    {
        $plainConfig = [];

        if (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['configuration.'])) {
            $plainConfig = GeneralUtility::removeDotsFromTS($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['configuration.']);
        }

        foreach ($plainConfig as $key => $imageVariantConfig) {
            $this->configs[$key] = self::generateImageVariantFromTypoScript($key, $imageVariantConfig);
        }
    }

    /**
     * @param string $key
     * @param array $config
     * @return PictureImageVariant
     */
    protected static function generateImageVariantFromTypoScript(string $key, array $config): PictureImageVariant
    {
        $imageVariant = GeneralUtility::makeInstance(PictureImageVariant::class, $key);

        if (!empty($config['defaultWidth'])) {
            $imageVariant->setDefaultWidth($config['defaultWidth']);
        }
        if (!empty($config['defaultHeight'])) {
            $imageVariant->setDefaultHeight($config['defaultHeight']);
        }

        if (!empty($config['sources']) && is_array($config['sources'])) {
            foreach ($config['sources'] as $source) {
                list($media, $sourceConfigs, $croppingVariantKey) = self::generateSourceConfigForImageVariant($source);

                if ($media && $sourceConfigs) {
                    $imageVariant->addSourceConfig($media, $sourceConfigs, $croppingVariantKey);
                }
            }
        }

        return $imageVariant;
    }

    /**
     * @param array $source
     * @return array
     */
    protected static function generateSourceConfigForImageVariant(array $source): array
    {
        $sourceConfig = [
            0 => '',
            1 => [],
            2 => 'default',
        ];

        if (empty($source['media']) || empty($source['sizes'])) {
            return $sourceConfig;
        }

        $sourceConfig[0] = $source['media'];
        foreach ($source['sizes'] as $density => $imageConfig) {
            $sourceConfig[1][$density] = $imageConfig;
        }
        if (!empty($source['croppingVariantKey'])) {
            $sourceConfig[2] = $source['croppingVariantKey'];
        }

        return $sourceConfig;
    }
}
