<?php
namespace Codemonkey1988\ResponsiveImages\Resource\Rendering\TagRenderer;

/***************************************************************
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Class AbstractTagRenderer
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Resource\Rendering\TagRenderer
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
abstract class AbstractTagRenderer
{
    /**
     * Names of all registered tag attributes
     *
     * @var array
     */
    private $tagAttributes = [];

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
     * @param \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder $tag
     * @return void
     */
    public function injectTag(TagBuilder $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Initializes the class.
     *
     * @return void
     */
    public function initialize()
    {
        $this->tag->reset();
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function addAttribute($name, $value)
    {
        $this->tagAttributes[$name] = $value;
    }

    /**
     * Renders the whole tag.
     *
     * @param string $content
     * @return string
     */
    public function render($content = '')
    {
        $this->tag->setTagName($this->tagName);
        $this->tag->setContent($content);
        $this->tag->addAttributes($this->tagAttributes);

        return $this->tag->render();
    }
}