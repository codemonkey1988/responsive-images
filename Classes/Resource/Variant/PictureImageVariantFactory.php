<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Variant;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class to store configuration for different picture tag configurations.
 */
class PictureImageVariantFactory
{
    /**
     * @var ContentObjectRenderer
     */
    protected ContentObjectRenderer $cObj;

    /**
     * @var array
     */
    protected array $settings;

    /**
     * @var array
     */
    protected array $variants = [];

    /**
     * @param ContentObjectRenderer $cObj
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ContentObjectRenderer $cObj, ConfigurationManager $configurationManager)
    {
        $this->cObj = $cObj;
        $this->cObj->start([]);

        try {
            $typoScript = $configurationManager->getConfiguration(
                ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
                'responsive_images'
            );
            $this->settings = $typoScript['plugin.']['tx_responsiveimages.']['settings.']['configuration.'] ?? [];
        } catch (InvalidConfigurationTypeException $e) {
            $this->settings = [];
        }
    }

    /**
     * @param string $key
     * @return PictureImageVariant
     * @throws NoSuchVariantException
     */
    public function get(string $key): PictureImageVariant
    {
        if (!isset($this->settings[$key . '.'])) {
            throw new NoSuchVariantException('No variant found with key "' . $key . '".', 1623538021);
        }
        if (!isset($this->variants[$key])) {
            $this->variants[$key] = $this->buildImageVariant($key, $this->settings[$key . '.']);
        }

        return $this->variants[$key];
    }

    /**
     * @param string $key
     * @param array $config
     * @return PictureImageVariant
     */
    protected function buildImageVariant(string $key, array $config): PictureImageVariant
    {
        /** @var PictureImageVariant $variant */
        $variant = GeneralUtility::makeInstance(PictureImageVariant::class, $key);
        $defaultWidth = $this->getParseDimensionValue('defaultWidth', $config);
        $defaultHeight = $this->getParseDimensionValue('defaultHeight', $config);

        if ($defaultWidth !== null) {
            $variant->setDefaultWidth($defaultWidth);
        }
        if ($defaultHeight !== null) {
            $variant->setDefaultHeight($defaultHeight);
        }
        if (isset($config['mimeTypes']) && is_string($config['mimeTypes']) && strlen($config['mimeTypes']) > 0) {
            $variant->setMimeTypes(GeneralUtility::trimExplode(',', $config['mimeTypes']));
        }
        if (isset($config['sources.']) && is_array($config['sources.'])) {
            foreach ($config['sources.'] as $source) {
                [$media, $sourceConfigs, $croppingVariantKey] = $this->buildSourceConfig($source);

                if ($media && $sourceConfigs) {
                    $variant->addSourceConfig($media, $sourceConfigs, $croppingVariantKey);
                }
            }
        }

        return $variant;
    }

    /**
     * @param array $source
     * @return array
     */
    protected function buildSourceConfig(array $source): array
    {
        $sourceConfig = [
            0 => '',
            1 => [],
            2 => 'default',
        ];

        if (empty($source['media']) || empty($source['sizes.'])) {
            return $sourceConfig;
        }

        $sourceConfig[0] = $source['media'];
        foreach ($source['sizes.'] as $density => $imageConfig) {
            $density = rtrim($density, '.');
            $width = $this->getParseDimensionValue('width', $imageConfig);
            $height = $this->getParseDimensionValue('height', $imageConfig);
            $sourceConfig[1][$density] = [];

            if ($width !== null) {
                $sourceConfig[1][$density]['width'] = $width;
            }
            if ($height !== null) {
                $sourceConfig[1][$density]['height'] = $height;
            }
            if (isset($imageConfig['quality']) && is_string($imageConfig['quality']) && strlen($imageConfig['quality']) > 0) {
                $sourceConfig[1][$density]['quality'] = $imageConfig['quality'];
            }
        }
        if (isset($source['croppingVariantKey']) && is_string($source['croppingVariantKey']) && strlen($source['croppingVariantKey']) > 0) {
            $sourceConfig[2] = $source['croppingVariantKey'];
        }

        return $sourceConfig;
    }

    /**
     * @param string $key
     * @param array $config
     * @return string|null
     */
    protected function getParseDimensionValue(string $key, array $config): ?string
    {
        if (isset($config[$key . '.']) && is_array($config[$key . '.'])) {
            if (isset($config[$key]) && is_string($config[$key]) && strlen($config[$key]) > 0) {
                return (string)$this->cObj->stdWrap($config[$key], $config[$key . '.']);
            }
            return (string)$this->cObj->stdWrap('', $config[$key . '.']);
        }
        return (string)$config[$key] ?? null;
    }
}
