<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Service;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

class ConfigurationService
{
    /**
     * @var array<string, mixed>
     */
    protected array $settings;

    public function __construct(ConfigurationManager $configurationManager)
    {
        try {
            $this->settings = $configurationManager->getConfiguration(
                ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
                'responsiveImages'
            );
        } catch (InvalidConfigurationTypeException $e) {
            $this->settings = [];
        }
    }

    public function isEnabled(): bool
    {
        if (isset($this->settings['enabled'])) {
            return (bool)$this->settings['enabled'];
        }
        return  true;
    }
}
