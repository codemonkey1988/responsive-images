<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Variant;

/**
 * Class to store configuration for different picture tag configurations.
 */
class Variant
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @var array
     */
    protected array $config;

    /**
     * PictureImageVariant constructor.
     *
     * @param string $key
     * @param array $config
     */
    public function __construct(string $key, array $config)
    {
        $this->key = $key;
        $this->config = $config;
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
    public function getConfig(): array
    {
        return $this->config;
    }
}
