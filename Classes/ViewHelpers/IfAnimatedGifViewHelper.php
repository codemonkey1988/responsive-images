<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Service\ImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class IfAnimatedGifViewHelper extends AbstractConditionViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('image', 'object', 'A FAL image object', true);
    }

    /**
     * This method decides if the condition is TRUE or FALSE. It can be overridden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexibility in overriding this method.
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        /** @var ImageService $imageService */
        $imageService = GeneralUtility::makeInstance(ImageService::class);

        return $imageService->isAnimatedGif($arguments['image']);
    }
}
