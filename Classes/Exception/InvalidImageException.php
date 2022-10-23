<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Exception;

use Codemonkey1988\ResponsiveImages\Exception;

class InvalidImageException extends Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, ?string $fileExtension = null)
    {
        if ($fileExtension !== null && empty($message)) {
            $message = sprintf(
                'The extension %s is not specified in $GLOBALS[\'TYPO3_CONF_VARS\'][\'GFX\'][\'imagefile_ext\'] as a valid image file extension and can not be processed.',
                $fileExtension
            );
        }
        parent::__construct($message, $code, $previous);
    }
}
