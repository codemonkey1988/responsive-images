<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Frontend;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FrontendRenderingTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'typo3/cms-fluid-styled-content',
    ];

    /**
     * @var array<non-empty-string>
     */
    protected array $testExtensionsToLoad = [
        'codemonkey1988/responsive-images',
    ];

    /**
     * @var array<string, non-empty-string>
     */
    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/responsive_images/Tests/Functional/Fixtures/Sites/' => 'typo3conf/sites',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileStorage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFile.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileMetadata.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Content.csv');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function configurationProvider(): array
    {
        return [
            'default configuration' => [
                'pagePath' => '/default',
                'imgClass' => 'image-default',
                'expectedSizes' => 2,
            ],
            'bootstrap configuration' => [
                'pagePath' => '/bootstrap',
                'imgClass' => 'image-bootstrap',
                'expectedSizes' => 4,
            ],
        ];
    }

    #[Test]
    #[DataProvider('configurationProvider')]
    public function renderShippedConfigurations(string $pagePath, string $imgClass, int $expectedSizes): void
    {
        $request = new InternalRequest('http://localhost' . $pagePath);
        $response = $this->executeFrontendSubRequest($request);

        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotEmpty($content);

        $document = \Dom\HtmlDocument::createFromString($content);
        $sourceTags = $document->getElementsByTagName('source');

        self::assertCount(2, $sourceTags);
        foreach ($sourceTags as $sourceTag) {
            $srcsets = $sourceTag->getAttribute('srcset');
            $sizes = $sourceTag->getAttribute('sizes');

            self::assertTrue($sourceTag->hasAttribute('media'));
            self::assertTrue($sourceTag->hasAttribute('srcset'));
            self::assertTrue($sourceTag->hasAttribute('sizes'));
            self::assertCount(7, GeneralUtility::trimExplode(',', (string)$srcsets, true));
            self::assertCount($expectedSizes, GeneralUtility::trimExplode(',', (string)$sizes, true));
        }

        $imgTags = $document->getElementsByTagName('img');
        self::assertCount(1, $imgTags, 'number of img tags');
        self::assertSame($imgClass, $imgTags->item(0)?->getAttribute('class'));
    }

    #[Test]
    public function renderSrcsetConfigurations(): void
    {
        $request = new InternalRequest('http://localhost/srcset-rendering');
        $response = $this->executeFrontendSubRequest($request);

        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotEmpty($content);

        $document = \Dom\HtmlDocument::createFromString($content);

        $imgTags = $document->getElementsByTagName('img');
        $imgTag = $imgTags->item(0);
        $srcsets = $imgTag?->getAttribute('srcset');

        self::assertCount(1, $imgTags, 'number of img tags');
        self::assertCount(7, GeneralUtility::trimExplode(',', (string)$srcsets, true));
        self::assertSame('image-srcset', $imgTag?->getAttribute('class'));
    }
}
