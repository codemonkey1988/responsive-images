<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\EventListener;

use Codemonkey1988\ResponsiveImages\Service\ConfigurationService;
use TYPO3\CMS\Core\Resource\Event\BeforeFileProcessingEvent;

class SkipImageProcessing
{
    /**
     * @var ConfigurationService
     */
    protected ConfigurationService $configurationService;

    /**
     * @param ConfigurationService $configurationService
     */
    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @param BeforeFileProcessingEvent $event
     */
    public function __invoke(BeforeFileProcessingEvent $event): void
    {
        if (!$this->configurationService->isProcessingEnabled()) {
            $event->getProcessedFile()->setUsesOriginalFile();
        }
    }
}
