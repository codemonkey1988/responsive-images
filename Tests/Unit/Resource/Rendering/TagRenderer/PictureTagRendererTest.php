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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\PictureTagRenderer;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\PictureTagRenderer
 */
class PictureTagRendererTest extends UnitTestCase
{
    /**
     * @var PictureTagRenderer
     */
    protected $pictureTagRenderer;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->pictureTagRenderer = new PictureTagRenderer();
        $this->pictureTagRenderer->injectTag(new TagBuilder());
    }

    /**
     * Test rendering a picture-tag without any attributes but with content.
     *
     * @test
     * @return void
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testRenderPictureWithoutAttributes()
    {
        $this->pictureTagRenderer->initialize();

        $result = $this->pictureTagRenderer->render('Picture tag content');

        $this->assertTrue(is_string($result));
        $this->assertEquals('<picture>Picture tag content</picture>', $result);
    }

    /**
     * Test rendering a picture-tag with class attribute and content.
     *
     * @test
     * @return void
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testRenderPictureWitClassAttribute()
    {
        $this->pictureTagRenderer->initialize();
        $this->pictureTagRenderer->addAttribute('class', 'my-class');

        $result = $this->pictureTagRenderer->render('Picture tag content');

        $this->assertTrue(is_string($result));
        $this->assertEquals('<picture class="my-class">Picture tag content</picture>', $result);
    }
}
