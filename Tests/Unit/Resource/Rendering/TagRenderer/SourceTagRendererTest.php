<?php
namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Resource\Service;

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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\SourceTagRenderer;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\SourceTagRenderer
 */
class SourceTagRendererTest extends UnitTestCase
{
    /**
     * @var SourceTagRenderer
     */
    protected $sourceTagRenderer;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->sourceTagRenderer = new SourceTagRenderer();
        $this->sourceTagRenderer->injectTag(new TagBuilder());
    }

    /**
     * Test rendering a source-tag with media and srcset attribute.
     *
     * @test
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return void
     */
    public function testRenderSourceWithDefaultAttributes()
    {
        $this->sourceTagRenderer->initialize();
        $this->sourceTagRenderer->addAttribute('media', '(max-width: 40em)');
        $this->sourceTagRenderer->addAttribute('srcset', 'test.jpg, text@2x.jpg');

        $result = $this->sourceTagRenderer->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<source media="(max-width: 40em)" srcset="test.jpg, text@2x.jpg" />', $result);
    }
}
