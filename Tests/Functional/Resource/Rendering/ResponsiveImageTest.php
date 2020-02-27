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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

    protected $typoScriptIncludes = [];

    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file_storage.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/pages.xml');

        $this->getDatabaseConnection()->delete('sys_file_processedfile', ['storage' => '1']);

        $this->typoScriptIncludes = [
            GeneralUtility::getFileAbsFileName('EXT:responsive_images/Configuration/TypoScript/setup.typoscript'),
            GeneralUtility::getFileAbsFileName('EXT:responsive_images/Configuration/TypoScript/DefaultConfiguration/setup.typoscript'),
        ];

        $this->setUpBackendUserFromFixture(1);
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImage()
    {
        $result = $this->getContentFromFrontendRequest(
            __DIR__ . '/../../Fixtures/config/pictureTag/pageConfig.typoscript'
        );

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertRegExp('/<source media=".*" srcset=".*" \/>/', $result);
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImageButDisabledPictureTag()
    {
        $result = $this->getContentFromFrontendRequest(
            __DIR__ . '/../../Fixtures/config/noPictureTag/pageConfig.typoscript'
        );

        $this->assertRegExp('/^<img src=".*" width="1920" height="1056" alt="" \/>$/', $result);
    }

    /**
     * @test
     */
    public function generatePictureTagForValidJpegImageWithoutImageProcessingDisabledByTypoScript()
    {
        $result = $this->getContentFromFrontendRequest(
            __DIR__ . '/../../Fixtures/config/pictureTagWithoutProcessing/pageConfig.typoscript'
        );

        $imagePaths = [
            '/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 1x',
            '/typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/example.jpg 2x',
        ];

        $this->assertRegExp('/^<picture>.*<\/picture>$/', $result);
        $this->assertContains('<source media="(max-width: 40em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 40.0625em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        $this->assertContains('<source media="(min-width: 64.0625em)" srcset="' . $imagePaths[0] . '" />', $result);
    }

    /**
     * @param string $additionalTypoScriptIncludes
     * @return string
     */
    protected function getContentFromFrontendRequest($additionalTypoScriptIncludes)
    {
        $this->setUpFrontendRootPage(1, array_merge(
            $this->typoScriptIncludes,
            [
                $additionalTypoScriptIncludes
            ]
        ));

        $response = $this->getFrontendResponse(1);

        return trim($response->getContent(), "\n ");
    }
}
