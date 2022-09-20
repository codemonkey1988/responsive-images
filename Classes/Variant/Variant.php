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
 *
 * @phpstan-type Config array{
 *     type?: string,
 *     media?: string,
 *     absolute?: string,
 *     'srcset.'?: array<string, array{
 *         prefix?: string,
 *     }>,
 *     'sizes.'?: array<string, array{
 *         assumedImageWidth?: string,
 *         viewportMediaCondition?: string,
 *     }>,
 *     'providedImageSizes.'?: array<string, array{
 *         width?: string|null,
 *         height?: string|null,
 *         minWidth?: string|null,
 *         minHeight?: string|null,
 *         maxWidth?: string|null,
 *         maxHeight?: string|null,
 *         quality?: string|null
 *     }>
 * }
 */
class Variant
{
    protected string $key;

    /**
     * @var Config
     */
    protected array $config;

    /**
     * PictureImageVariant constructor.
     *
     * @param Config $config
     */
    public function __construct(string $key, array $config)
    {
        $this->key = $key;
        $this->config = $config;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return Config
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
