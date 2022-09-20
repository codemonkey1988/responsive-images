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
 * Class to store configuration for different picture tag configurations.
 * @deprecated
 */
class PictureImageConfiguration extends Variant
{
    /**
     * @var array
     */
    protected array $sources = [];

    /**
     * @var string
     */
    protected string $defaultWidth = '1920';

    /**
     * @var string
     */
    protected string $defaultHeight = '';

    /**
     * @var array
     */
    protected array $mimeTypes = [];

    public function __construct(string $key, array $config)
    {
        parent::__construct($key, $config);
        trigger_error(
            'Class ' . __CLASS__ . ' is deprecated and will be removed in 4.0. Please migrate your configuration to variants.',
            E_USER_DEPRECATED
        );
        $this->defaultWidth = $this->config['defaultWidth'] ?? '1920';
        $this->defaultHeight = $this->config['defaultHeight'] ?? '';
        if (isset($this->config['mimeTypes']) && is_string($this->config['mimeTypes']) && strlen($this->config['mimeTypes']) > 0) {
            $this->setMimeTypes(GeneralUtility::trimExplode(',', $this->config['mimeTypes']));
        }
        if (isset($this->config['sources.']) && is_array($this->config['sources.'])) {
            foreach ($this->config['sources.'] as $source) {
                [$media, $sourceConfigs, $croppingVariantKey] = $this->buildSourceConfig($source);
                if ($media && $sourceConfigs) {
                    $this->addSourceConfig($media, $sourceConfigs, $croppingVariantKey);
                }
            }
        }
    }

    /**
     * @param string $media
     * @param array  $srcsets
     * @param string $croppingVariantKey
     * @return Variant
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
     * @return Variant
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
     * @return Variant
     */
    public function setDefaultHeight(string $defaultHeight): self
    {
        $this->defaultHeight = $defaultHeight;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    /**
     * @param string[] $mimeTypes
     */
    public function setMimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
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
            $width = $imageConfig['with'] ?? '';
            $height = $imageConfig['height'] ?? '';
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
}
