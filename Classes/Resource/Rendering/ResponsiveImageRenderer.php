<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Resource\Rendering;

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

use Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureVariantsRegistry;
use Codemonkey1988\ResponsiveImages\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class to render a picture tag with different sources and a fallback image.
 */
class ResponsiveImageRenderer implements FileRendererInterface
{
    const DEFAULT_IMAGE_VARIANT_KEY = 'default';
    const REGISTER_IMAGE_VARIANT_KEY = 'IMAGE_VARIANT_KEY';
    const REGISTER_IMAGE_RELATVE_WIDTH_KEY = 'IMAGE_RELATIVE_WIDTH_KEY';
    const OPTIONS_IMAGE_RELATVE_WIDTH_KEY = 'relativeScalingWidth';

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 5;
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        /** @var EnvironmentService $evironmentService */
        $evironmentService = GeneralUtility::makeInstance(EnvironmentService::class);
        $registry = PictureVariantsRegistry::getInstance();
        $supportedMimeTypes = ConfigurationUtility::getExtensionConfig()['supportedMimeTypes'];

        $enabled = ConfigurationUtility::isEnabled();

        return $enabled && $evironmentService->isEnvironmentInFrontendMode()
            && $registry->imageVariantKeyExists(self::DEFAULT_IMAGE_VARIANT_KEY)
            && GeneralUtility::inList($supportedMimeTypes, $file->getMimeType());
    }

    /**
     * Renders a responsive image tag.
     *
     * @param FileInterface $file
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false): string
    {
        if (!array_key_exists(self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY, $options)
            && isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATVE_WIDTH_KEY])
        ) {
            $options[self::OPTIONS_IMAGE_RELATVE_WIDTH_KEY] = (float)$GLOBALS['TSFE']->register[self::REGISTER_IMAGE_RELATVE_WIDTH_KEY];
        }

        $view = $this->initializeView();
        $view->assignMultiple([
            'isAnimatedGif' => $this->isAnimatedGif($file),
            'config' => $this->getConfig(),
            'data' => $GLOBALS['TSFE']->cObj->data,
            'file' => $file,
            'options' => $options,
        ]);

        return $view->render('pictureTag');
    }

    /**
     * @return StandaloneView
     */
    protected function initializeView(): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class, $GLOBALS['TSFE']->cObj);

        if (!empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.'])) {
            $view->setTemplateRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['templateRootPaths.']);
            $view->setPartialRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['partialRootPaths.']);
            $view->setLayoutRootPaths($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['view.']['layoutRootPaths.']);
        }

        return $view;
    }

    /**
     * @return PictureImageVariant
     */
    protected function getConfig(): PictureImageVariant
    {
        $imageVarientConfigKey = self::DEFAULT_IMAGE_VARIANT_KEY;
        $registry = PictureVariantsRegistry::getInstance();

        if (isset($GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY])
            && $registry->imageVariantKeyExists(
                $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY]
            )
        ) {
            $imageVarientConfigKey = $GLOBALS['TSFE']->register[self::REGISTER_IMAGE_VARIANT_KEY];
        }

        return $registry->getImageVariant($imageVarientConfigKey);
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    protected function isAnimatedGif(FileInterface $file): bool
    {
        return GeneralUtility::makeInstance(\Codemonkey1988\ResponsiveImages\Resource\Service\ImageService::class)->isAnimatedGif($file);
    }
}
