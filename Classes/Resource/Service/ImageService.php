<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Service;

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

use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Service class for image functions.
 */
class ImageService
{
    /**
     * Checks if the given file is an aniamted gif.
     * See https://secure.php.net/manual/en/function.imagecreatefromgif.php#59787
     *
     * @param FileInterface $file
     * @return bool
     */
    public function isAnimatedGif(FileInterface $file): bool
    {
        if ($file->getMimeType() === 'image/gif') {
            $filecontents = file_get_contents($file->getForLocalProcessing(false));
            $strLoc = 0;
            $count = 0;

            // There is no point in continuing after we find a 2nd frame
            while ($count < 2) {
                $where1 = strpos($filecontents, "\x00\x21\xF9\x04", $strLoc);
                if ($where1 === false) {
                    break;
                }
                $strLoc = $where1 + 1;
                $where2 = strpos($filecontents, "\x00\x2C", $strLoc);
                if ($where2 === false) {
                    break;
                }
                if ($where1 + 8 == $where2) {
                    $count++;
                }
                $strLoc = $where2 + 1;
            }

            return $count > 1;
        }

        return false;
    }
}
