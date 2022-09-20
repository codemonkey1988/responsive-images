<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Utility;

/**
 * Utility class for this extension.
 */
class ConfigurationUtility
{
    /**
     * @return bool
     */
    public static function isEnabled(): bool
    {
        $enabled = true;
        $enabledByEnv = getenv('RESPONSIVE_IMAGES_ENABLED');

        if ($enabledByEnv !== false) {
            $enabled = (bool)$enabledByEnv;
        } elseif (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.'])) {
            $enabled = (bool)$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['enabled'];
        }

        return $enabled;
    }

    /**
     * @return bool
     */
    public static function isProcessingEnabled(): bool
    {
        $processing = true;
        $processingByEnv = getenv('RESPONSIVE_IMAGES_PROCESSING');

        if ($processingByEnv !== false) {
            $processing = (bool)$processingByEnv;
        } elseif (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.'])) {
            $processing = (bool)$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['processing'];
        }

        return $processing;
    }
}
