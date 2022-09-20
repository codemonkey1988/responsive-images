<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Resource\Rendering;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test class for \Codemonkey1988\ResponsiveImages\Resource\Rendering\ResponsiveImageRenderer
 */
class ResponsiveImageTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/responsive_images',
    ];

    protected $typoScriptIncludes = [];

    protected $pathsToProvideInTestInstance = [
        'typo3conf/ext/responsive_images/Tests/Functional/Fixtures/fileadmin/' => 'fileadmin/',
    ];

    protected $pathsToLinkInTestInstance = [
        'typo3conf/ext/responsive_images/Tests/Functional/Fixtures/config/sites' => 'typo3conf/sites',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file_storage.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/sys_file.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/pages.xml');

        /** @var Connection $connection */
        $connection = $this->getConnectionPool()->getConnectionForTable('sys_file_processedfile');
        $connection->truncate('sys_file_processedfile');

        $this->typoScriptIncludes = [
            'EXT:responsive_images/Configuration/TypoScript/setup.typoscript',
            'EXT:responsive_images/Configuration/TypoScript/DefaultConfiguration/setup.typoscript',
            'EXT:responsive_images/Tests/Functional/Fixtures/config/TypoScript/disableProcessing.typoscript',
        ];

        $this->setUpBackendUserFromFixture(1);
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImage()
    {
        $result = $this->getContentFromFrontendRequest(
            'EXT:responsive_images/Tests/Functional/Fixtures/config/pictureTag/pageConfig.typoscript'
        );

        $imagePaths = [
            '/fileadmin/example.jpg 1x',
            '/fileadmin/example.jpg 2x',
        ];

        self::assertMatchesRegularExpression('/^<picture>.*<\/picture>$/', $result);
        self::assertStringContainsString('<source media="(max-width: 40em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        self::assertStringContainsString('<source media="(min-width: 40.0625em)" srcset="' . implode(',', $imagePaths) . '" />', $result);
        self::assertStringContainsString('<source media="(min-width: 64.0625em)" srcset="' . $imagePaths[0] . '" />', $result);
    }

    /**
     * @test
     */
    public function generateImageTagForValidJpegImageButDisabledPictureTag()
    {
        $result = $this->getContentFromFrontendRequest(
            'EXT:responsive_images/Tests/Functional/Fixtures/config/noPictureTag/pageConfig.typoscript'
        );

        self::assertSame('<img src="/fileadmin/example.jpg" width="1920" height="1056" alt="" />', $result);
    }

    /**
     * @param string $additionalTypoScriptIncludes
     * @return string
     */
    protected function getContentFromFrontendRequest(string $additionalTypoScriptIncludes): string
    {
        $this->setUpFrontendRootPage(1, [
            'setup' => array_merge(
                $this->typoScriptIncludes,
                [
                    $additionalTypoScriptIncludes,
                ]
            )
        ]);

        $response = $this->getFrontendResponse(1);
        return trim($response->getContent(), "\n ");
    }
}
