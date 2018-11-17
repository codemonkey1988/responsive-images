<?php
namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Resource\Rendering;

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

use Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureImageVariant;
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureVariantsRegistry;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer
 */
class ResponsiveImageTest extends FunctionalTestCase
{
    protected $coreExtensionsToLoad = [
        'recordlist'
    ];

    protected $testExtensionsToLoad = [
        'typo3conf/ext/responsive_images'
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file_storage.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file.xml');

        $typoscriptIncludes = [
            GeneralUtility::getFileAbsFileName('EXT:responsive_images/Configuration/TypoScript/setup.typoscript'),
            GeneralUtility::getFileAbsFileName('EXT:responsive_images/Configuration/TypoScript/DefaultConfiguration/setup.typoscript'),
        ];

        $tsfeMock = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tsfeMock->tmpl = new TemplateService();
        // Flux hooks into the process triggered by this method.
        $tsfeMock->tmpl->processTemplate([], '', 1);
        $tsfeMock->tmpl->setup = [];

        foreach ($typoscriptIncludes as $additionalTypoScript) {
            $config = GeneralUtility::makeInstance(TypoScriptParser::class);
            $config->parse(file_get_contents($additionalTypoScript));
            ArrayUtility::mergeRecursiveWithOverrule(
                $tsfeMock->tmpl->setup,
                $config->setup
            );
        }

        $contentObjectRenderer = new ContentObjectRenderer($tsfeMock);
        $contentObjectRenderer->data = [];

        $tsfeMock->cObj = $contentObjectRenderer;

        $GLOBALS['TSFE'] = $tsfeMock;
        $GLOBALS['BE_USER'] = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        $GLOBALS['BE_USER']->expects($this->any())
            ->method('isAdmin')
            ->willReturn(true);
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($GLOBALS['TSFE']);
        unset($GLOBALS['BE_USER']);

        $registry = PictureVariantsRegistry::getInstance();
        $registry->removeAllImageVariants();
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImage()
    {
        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $file = $fileRepository->findByUid(1);

        $subject = GeneralUtility::makeInstance(ResponsiveImageRenderer::class);
        $result = $subject->render($file, 0, 0);

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertRegExp('/<source media=".*" srcset=".*" \/>/', $result);
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImageButDisabledPictureTag()
    {
        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $file = $fileRepository->findByUid(1);

        $subject = GeneralUtility::makeInstance(ResponsiveImageRenderer::class);
        $result = $subject->render($file, 0, 0, ['disablePictureTag' => true]);

        $this->assertRegExp('/^<img src=".*" width="1920" height="1056" alt="" \/>$/', $result);
    }
}
