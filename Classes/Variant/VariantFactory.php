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
 */
class VariantFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';

    /**
     * @var array
     */
    protected array $variants = [];

    /**
     * @var array
     * @deprecated
     */
    protected array $configuration = [];

    /**
     * @param ConfigurationManager $configurationManager
     */
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
        $this->buildConfiguration($settings);
    }

    /**
     * @param string $key
     * @return bool
     */
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
     * @param string|null $key
     * @return Variant
     * @throws NoSuchVariantException
     */
    public function get(?string $key = null): Variant
    {
        if ($key === null || strlen($key) === 0) {
            $key = $this->getKeyFromRegistry();
        }

        if (array_key_exists($key, $this->configuration)) {
            $this->logger->info('Using deprecated configuration with key "' . $key . '".');
            return $this->configuration[$key];
        }
        if (array_key_exists($key, $this->variants)) {
            return $this->variants[$key];
        }

        throw new NoSuchVariantException('No configuration or variant found with key "' . $key . '".', 1623538021);
    }

    /**
     * @param PictureImageConfiguration $configuration
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function addConfiguration(PictureImageConfiguration $configuration): void
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        $this->configuration[$configuration->getKey()] = $configuration;
    }

    /**
     * @param string $key
     * @return bool
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function hasConfiguration(string $key): bool
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        return array_key_exists($key, $this->configuration);
    }

    /**
     * @param string $key
     * @return PictureImageConfiguration|null
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function getConfiguration(string $key): ?PictureImageConfiguration
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        if ($this->hasConfiguration($key)) {
            return $this->configuration[$key];
        }

        return null;
    }

    /**
     * @return array
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function getAllConfiguration(): array
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        return $this->configuration;
    }

    /**
     * @param string $key
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function removeConfiguration(string $key): void
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        if ($this->hasConfiguration($key)) {
            unset($this->configuration[$key]);
        }
    }

    /**
     * @deprecated This method will be removed in 4.0. Please use variants and register them using TypoScript.
     */
    public function truncateConfiguration(): void
    {
        trigger_error(
            'This method will be removed in 4.0. Please use variants and register them using TypoScript.',
            E_USER_DEPRECATED
        );
        $this->configuration = [];
    }

    /**
     * @param array $settings
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

    /**
     * @deprecated Using configurations for responsive_images is deprecated. Use settings.variants instea
     * @param array $settings
     */
    protected function buildConfiguration(array $settings): void
    {
        if (array_key_exists('configuration.', $settings) && is_array($settings['configuration.'])) {
            foreach ($settings['configuration.'] as $key => $config) {
                $key = rtrim($key, '.');
                $this->configuration[$key] = GeneralUtility::makeInstance(
                    PictureImageConfiguration::class,
                    $key,
                    $config
                );
            }
        }
    }

    /**
     * @return string
     */
    protected function getKeyFromRegistry(): string
    {
        $key = self::DEFAULT_IMAGE_VARIANT_KEY;

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])) {
            $key = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
        }

        return $key;
    }
}
