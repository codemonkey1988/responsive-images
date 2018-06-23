<?php
declare(strict_types=1);
namespace Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Class AbstractTagRenderer
 */
abstract class AbstractTagRenderer
{
    /**
     * Tag builder instance
     *
     * @var \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder
     */
    protected $tag = null;
    /**
     * name of the tag to be created by this view helper
     *
     * @var string
     */
    protected $tagName = '';
    /**
     * Names of all registered tag attributes
     *
     * @var array
     */
    private $tagAttributes = [];

    /**
     * @param \TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder $tag
     * @return void
     */
    public function injectTag(TagBuilder $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * Initializes the class.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->tag->reset();
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function addAttribute(string $name, string $value): void
    {
        $this->tagAttributes[$name] = $value;
    }

    /**
     * Renders the whole tag.
     *
     * @param string $content
     * @return string
     */
    public function render(string $content = ''): string
    {
        $this->tag->setTagName($this->tagName);
        $this->tag->setContent($content);
        $this->tag->addAttributes($this->tagAttributes);

        return $this->tag->render();
    }
}
