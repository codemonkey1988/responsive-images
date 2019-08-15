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
     */
    public function removeAllImageVariants()
    {
        $this->configs = [];
    }

    /**
     * @param string $key
     * @return void
     */
    public function removeImageVariant(string $key)
    {
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }

    /**
     * @return void
     */
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

        if (!empty($config['layoutKey'])) {
            $imageVariant->setLayoutKey($config['layoutKey']);
        }
        if (!empty($config['defaultWidth'])) {
            $imageVariant->setDefaultWidth($config['defaultWidth']);
        }
        if (!empty($config['defaultHeight'])) {
            $imageVariant->setDefaultHeight($config['defaultHeight']);
        }

        if (!empty($config['sources']) && is_array($config['sources'])) {
            foreach ($config['sources'] as $source) {
                list($media, $sourceConfigs, $croppingVariantKey) = self::generateSourceConfigForImageVariant($source);

                if ($sourceConfigs) {
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

        if (empty($source['sizes'])) {
            return $sourceConfig;
        }

        $sourceConfig[0] = $source['media'] ?: '';
        foreach ($source['sizes'] as $density => $imageConfig) {
            $sourceConfig[1][$density] = $imageConfig;
        }
        if (!empty($source['croppingVariantKey'])) {
            $sourceConfig[2] = $source['croppingVariantKey'];
        }

        return $sourceConfig;
    }
}
