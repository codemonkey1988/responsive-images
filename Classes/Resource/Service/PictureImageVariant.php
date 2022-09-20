<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @var array
     */
    protected $mimeTypes = [];

    /**
     * PictureImageVariant constructor.
     *
     * @param string $key
     * @param array $config
     */
    public function __construct(string $key, array $config)
    {
        $this->key = $key;
        $this->initializeFromArray($config);
    }

    /**
     * @param string $media
     * @param array  $srcsets
     * @param string $croppingVariantKey
     * @return PictureImageVariant
     */
    public function addSourceConfig(string $media, array $srcsets, string $croppingVariantKey = 'default'): self
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
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getAllSourceConfig(): array
    {
        return $this->sources;
    }

    /**
     * @return string
     */
    public function getDefaultWidth(): string
    {
        return $this->defaultWidth;
    }

    /**
     * @param string $defaultWidth
     * @return PictureImageVariant
     */
    public function setDefaultWidth(string $defaultWidth): self
    {
        $this->defaultWidth = $defaultWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultHeight(): string
    {
        return $this->defaultHeight;
    }

    /**
     * @param string $defaultHeight
     * @return PictureImageVariant
     */
    public function setDefaultHeight(string $defaultHeight): self
    {
        $this->defaultHeight = $defaultHeight;

        return $this;
    }

    /**
     * @return array
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    /**
     * @param array $mimeTypes
     * @return PictureImageVariant
     */
    public function setMimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }

    /**
     * @param array $config
     */
    protected function initializeFromArray(array $config): void
    {
        if (isset($config['defaultWidth']) && is_string($config['defaultWidth']) && strlen($config['defaultWidth']) > 0) {
            $this->setDefaultWidth($config['defaultWidth']);
        }
        if (isset($config['defaultHeight']) && is_string($config['defaultHeight']) && strlen($config['defaultHeight']) > 0) {
            $this->setDefaultHeight($config['defaultHeight']);
        }
        if (isset($config['mimeTypes']) && is_string($config['mimeTypes']) && strlen($config['mimeTypes']) > 0) {
            $this->setMimeTypes(GeneralUtility::trimExplode(',', $config['mimeTypes']));
        }
        if (isset($config['sources']) && is_array($config['sources'])) {
            foreach ($config['sources'] as $source) {
                [$media, $sourceConfigs, $croppingVariantKey] = $this->buildSourceConfig($source);

                if ($media && $sourceConfigs) {
                    $this->addSourceConfig($media, $sourceConfigs, $croppingVariantKey);
                }
            }
        }
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
