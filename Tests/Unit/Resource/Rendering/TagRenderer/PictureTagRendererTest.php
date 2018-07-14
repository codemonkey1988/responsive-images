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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer\PictureTagRenderer
 */
class PictureTagRendererTest extends UnitTestCase
{
    /**
     * @var PictureTagRenderer
     */
    protected $subject;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->subject = new PictureTagRenderer();
        $this->subject->injectTag(new TagBuilder());
    }

    /**
     * Test rendering a picture-tag without any attributes but with content.
     *
     * @test
     */
    public function renderPictureWithoutAttributes()
    {
        $this->subject->initialize();

        $result = $this->subject->render('Picture tag content');

        $this->assertTrue(is_string($result));
        $this->assertEquals('<picture>Picture tag content</picture>', $result);
    }

    /**
     * Test rendering a picture-tag with class attribute and content.
     *
     * @test
     */
    public function renderPictureWitClassAttribute()
    {
        $this->subject->initialize();
        $this->subject->addAttribute('class', 'my-class');

        $result = $this->subject->render('Picture tag content');

        $this->assertTrue(is_string($result));
        $this->assertEquals('<picture class="my-class">Picture tag content</picture>', $result);
    }
}
