<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Signal;

/*
 * This file is part of the TYPO3 responsive images project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 *
 */

use TYPO3\CMS\Core\Resource\Driver\DriverInterface;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\Service\FileProcessingService;

/**
 * Register class to add new image variants. Should be used in ext_localconf.php
 */
class FileProcessing
{
    /**
     * @param FileProcessingService $ref
     * @param DriverInterface $driver
     * @param ProcessedFile $processedFile
     * @param FileInterface $file
     * @param string $context
     * @param array $configuration
     */
    public function preProcess(FileProcessingService $ref, DriverInterface $driver, ProcessedFile $processedFile, FileInterface $file, string $context, array $configuration)
    {
        if (!empty($processedFile->getProcessingConfiguration()['skipProcessing'])) {
            $processedFile->setUsesOriginalFile();
        }
    }
}
