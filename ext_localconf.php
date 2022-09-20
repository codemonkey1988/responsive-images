<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied');
}

call_user_func(function () {
    /** @var \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry $rendererRegistry */
    $rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
    $rendererRegistry->registerRendererClass(\Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer::class);
});
