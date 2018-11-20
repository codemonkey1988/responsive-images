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
use Codemonkey1988\ResponsiveImages\Resource\Service\PictureVariantsRegistry;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer
 */
class ResponsiveImageTest extends FunctionalTestCase
{
    protected $coreExtensionsToLoad = [
        'recordlist',
    ];

    protected $testExtensionsToLoad = [
        'typo3conf/ext/responsive_images',
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
        $result = trim($subject->render($file, 0, 0), "\n ");

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
        $result = trim($subject->render($file, 0, 0, ['disablePictureTag' => true]), "\n ");

        $this->assertRegExp('/^<img src=".*" width="1920" height="1056" alt="" \/>$/', $result);
    }

    /**
     * @test
     */
    public function generatePictureTagForValidJpegImageWithoutImageProcessingDisabledByEnv()
    {
        putenv('RESPONSIVE_IMAGES_PROCESSING=0');

        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $file = $fileRepository->findByUid(1);

        $subject = GeneralUtility::makeInstance(ResponsiveImageRenderer::class);
        $result = trim($subject->render($file, 0, 0), "\n ");

        putenv('RESPONSIVE_IMAGES_PROCESSING');

        $imagePaths = [
            '.Build/bin/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 1x',
            '.Build/bin/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 2x',
        ];

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertContains('<source media="(max-width: 40em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 40.0625em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 64.0625em)" srcset="' . $imagePaths[0] . '" />', $result);
    }

    /**
     * @test
     */
    public function generatePictureTagForValidJpegImageWithoutImageProcessingDisabledByTypoScript()
    {
        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_responsiveimages.']['settings.']['processing'] = '0';

        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $file = $fileRepository->findByUid(1);

        $subject = GeneralUtility::makeInstance(ResponsiveImageRenderer::class);
        $result = trim($subject->render($file, 0, 0), "\n ");

        $imagePaths = [
            '.Build/bin/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 1x',
            '.Build/bin/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 2x',
        ];

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertContains('<source media="(max-width: 40em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 40.0625em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 64.0625em)" srcset="' . $imagePaths[0] . '" />', $result);
    }
}
