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
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\SourceTagRenderer
 */
class SourceTagRendererTest extends UnitTestCase
{
    /**
     * @var SourceTagRenderer
     */
    protected $subject;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->subject = new SourceTagRenderer();
        $this->subject->injectTag(new TagBuilder());
    }

    /**
     * Test rendering a source-tag with media and srcset attribute.
     *
     * @test
     */
    public function renderSourceWithDefaultAttributes()
    {
        $this->subject->initialize();
        $this->subject->addAttribute('media', '(max-width: 40em)');
        $this->subject->addAttribute('srcset', 'test.jpg, text@2x.jpg');

        $result = $this->subject->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<source media="(max-width: 40em)" srcset="test.jpg, text@2x.jpg" />', $result);
    }
}
