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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\ImgTagRenderer
 */
class ImgTagRendererTest extends UnitTestCase
{
    /**
     * @var ImgTagRenderer
     */
    protected $imgTagRenderer;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->imgTagRenderer = new ImgTagRenderer();
        $this->imgTagRenderer->injectTag(new TagBuilder());
    }

    /**
     * Test rendering an img-tag with src and alt attribute.
     *
     * @test
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return void
     */
    public function testRenderImageTagWithMinimumAttributes()
    {
        $this->imgTagRenderer->initialize();
        $this->imgTagRenderer->addAttribute('src', 'test.jpg');
        $this->imgTagRenderer->addAttribute('alt', 'Test image');

        $result = $this->imgTagRenderer->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<img src="test.jpg" alt="Test image" />', $result);
    }

    /**
     * Test rendering an img-tag with src, alt, width, height and title attribute.
     *
     * @test
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return void
     */
    public function testRenderImageTagWithStandardAttributes()
    {
        $this->imgTagRenderer->initialize();
        $this->imgTagRenderer->addAttribute('src', 'test.jpg');
        $this->imgTagRenderer->addAttribute('alt', 'Test image');
        $this->imgTagRenderer->addAttribute('width', '1920');
        $this->imgTagRenderer->addAttribute('height', '1080');
        $this->imgTagRenderer->addAttribute('title', 'Title of test image');

        $result = $this->imgTagRenderer->render();

        $this->assertTrue(is_string($result));
        $this->assertEquals('<img src="test.jpg" alt="Test image" width="1920" height="1080" title="Title of test image" />', $result);
    }
}
