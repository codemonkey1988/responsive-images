<?php
namespace Codemonkey1988\ResponsiveImages\Resource\Service;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PictureVariantsRegistry
 *
 * @package    Codemonkey1988\ResponsiveImages
 * @subpackage Resource\Rendering
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class PictureVariantsRegistry implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var array
     */
    protected $configs = array();

    /**
     * @return PictureVariantsRegistry
     */
    public static function getInstance()
    {
        return GeneralUtility::makeInstance(PictureVariantsRegistry::class);
    }

    /**
     * @param PictureImageVariant $imageVariant
     * @return void
     */
    public function registerImageVariant(PictureImageVariant $imageVariant)
    {
        $this->configs[$imageVariant->getKey()] = $imageVariant;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function imageVariantKeyExists($key)
    {
        return isset($this->configs[$key]);
    }

    /**
     * @return PictureImageVariant[]
     */
    public function getAllImageVariants()
    {
        return $this->configs;
    }

    /**
     * @param string $key
     * @return PictureImageVariant|null
     */
    public function getImageVariant($key)
    {
        return isset($this->configs[$key]) ? $this->configs[$key] : null;
    }

    /**
     * @return void
     */
    public function removeAllImageVariants()
    {
        $this->configs = array();
    }

    /**
     * @param string $key
     * @return void
     */
    public function removeImageVariant($key)
    {
        if (isset($this->configs[$key])) {
            unset($this->configs[$key]);
        }
    }
}
