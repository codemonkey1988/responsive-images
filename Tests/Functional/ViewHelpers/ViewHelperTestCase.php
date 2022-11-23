<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class ViewHelperTestCase extends FunctionalTestCase
{
    protected function buildRenderingContext(): RenderingContext
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['r'] = [
            'Codemonkey1988\\ResponsiveImages\\ViewHelpers'
        ];

        // For v11+
        if (class_exists(\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory::class)) {
            /** @var \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory $factory */
            $factory = $this->get(\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory::class);
            return $factory->create();
        }

        // Fallback for v10
        return new RenderingContext();
    }
}
