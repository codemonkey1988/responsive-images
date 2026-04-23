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
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\RegisterStack;
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

        /** @var ContentObjectRenderer $cObj */
        $cObj = $this->get(ContentObjectRenderer::class);
        $serverRequest = $this->buildFakeServerRequest()
            ->withAttribute('currentContentObject', $cObj)
            ->withAttribute('extbase', new ExtbaseRequestParameters());
        $typo3Version = new Typo3Version();
        if ($typo3Version->getMajorVersion() >= 14) {
            $serverRequest = $serverRequest->withAttribute('frontend.register.stack', new RegisterStack());
        }

        $cObj->setRequest($serverRequest);
        $cObj->start(['uid' => 123], 'tt_content');

        /** @var RenderingContextFactory $factory */
        $factory = $this->get(RenderingContextFactory::class);

        return $factory->create([], $serverRequest);
    }
}
