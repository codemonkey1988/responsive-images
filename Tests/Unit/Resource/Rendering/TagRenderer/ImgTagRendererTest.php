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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\ImgTagRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\ImgTagRenderer
 */
class ImgTagRendererTest extends UnitTestCase
{
    /**
     * @var ImgTagRenderer
     */
    protected $subject;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->subject = new ImgTagRenderer();
        $this->subject->injectTag(new TagBuilder());
    }

    /**
     * Test rendering an img-tag with src and alt attribute.
     *
     * @test
     */
    public function renderImageTagWithMinimumAttributes()
    {
        $this->subject->initialize();
        $this->subject->addAttribute('src', 'test.jpg');
        $this->subject->addAttribute('alt', 'Test image');

        $result = $this->subject->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<img src="test.jpg" alt="Test image" />', $result);
    }

    /**
     * Test rendering an img-tag with src, alt, width, height and title attribute.
     *
     * @test
     */
    public function renderImageTagWithStandardAttributes()
    {
        $this->subject->initialize();
        $this->subject->addAttribute('src', 'test.jpg');
        $this->subject->addAttribute('alt', 'Test image');
        $this->subject->addAttribute('width', '1920');
        $this->subject->addAttribute('height', '1080');
        $this->subject->addAttribute('title', 'Title of test image');

        $result = $this->subject->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<img src="test.jpg" alt="Test image" width="1920" height="1080" title="Title of test image" />', $result);
    }
}
