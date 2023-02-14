<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Variant;

use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

/**
 * Class to store configuration for different picture tag configurations.
 *
 * @phpstan-type Settings array{
 *     'variants.'?: array<string, mixed>,
 * }
 */
class VariantFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';

    /**
     * @var array<string, Variant>
     */
    protected array $variants = [];

    public function __construct(ConfigurationManager $configurationManager)
    {
        try {
            $typoScript = $configurationManager->getConfiguration(
                ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            $settings = $typoScript['plugin.']['tx_responsiveimages.']['settings.'] ?? [];
        } catch (InvalidConfigurationTypeException $e) {
            $settings = [];
        }

        $this->buildVariants($settings);
    }

    public function has(string $key): bool
    {
        try {
            $this->get($key);
            return true;
        } catch (NoSuchVariantException $e) {
            return false;
        }
    }

    /**
     * @throws NoSuchVariantException
     */
    public function get(?string $key = null): Variant
    {
        if ($key === null || strlen($key) === 0) {
            $key = $this->getKeyFromRegistry();
        }

        if (array_key_exists($key, $this->variants)) {
            return $this->variants[$key];
        }

        throw new NoSuchVariantException('No configuration or variant found with key "' . $key . '".', 1623538021);
    }

    /**
     * @param Settings $settings
     */
    protected function buildVariants(array $settings): void
    {
        if (array_key_exists('variants.', $settings) && is_array($settings['variants.'])) {
            foreach ($settings['variants.'] as $key => $config) {
                $key = rtrim($key, '.');
                $this->variants[$key] = GeneralUtility::makeInstance(
                    Variant::class,
                    $key,
                    $config
                );
            }
        }
    }

    protected function getKeyFromRegistry(): string
    {
        $key = self::DEFAULT_IMAGE_VARIANT_KEY;

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])) {
            $key = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
        }

        return $key;
    }
}
