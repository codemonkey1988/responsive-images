<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\EventListener;

use TYPO3\CMS\Core\Resource\Event\BeforeFileProcessingEvent;

class SkipImageProcessing
{
    /**
     * @param BeforeFileProcessingEvent $event
     */
    public function __invoke(BeforeFileProcessingEvent $event): void
    {
        if (!empty($event->getProcessedFile()->getProcessingConfiguration()['skipProcessing'])) {
            $event->getProcessedFile()->setUsesOriginalFile();
        }
    }
}
