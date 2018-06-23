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

/**
 * Class to render a html source tag.
 */
class SourceTagRenderer extends AbstractTagRenderer
{
    /**
     * @var string
     */
    protected $tagName = 'source';
}
