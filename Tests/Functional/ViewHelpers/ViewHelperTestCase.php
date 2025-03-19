<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Tests\Functional\ServerRequestTrait;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class ViewHelperTestCase extends FunctionalTestCase
{
    use ServerRequestTrait;

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'codemonkey1988/responsive-images',
    ];

    protected function buildRenderingContext(): RenderingContext
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['r'] = [
            'Codemonkey1988\\ResponsiveImages\\ViewHelpers'
        ];

        $cObj = new ContentObjectRenderer();
        $cObj->start(['uid' => 123], 'tt_content');
        $serverRequest = $this->buildFakeServerRequest()
            ->withAttribute('currentContentObject', $cObj)
            ->withAttribute('extbase', new ExtbaseRequestParameters());
        $cObj->setRequest($serverRequest);

        /** @var \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory $factory */
        $factory = $this->get(\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory::class);
        $context = $factory->create();
        $context->setRequest($serverRequest);

        return $context;
    }
}
