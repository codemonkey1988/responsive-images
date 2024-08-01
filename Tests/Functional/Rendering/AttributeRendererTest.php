<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Rendering;

use Codemonkey1988\ResponsiveImages\Rendering\AttributeRenderer;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(AttributeRenderer::class)]
class AttributeRendererTest extends FunctionalTestCase
{
    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'codemonkey1988/responsive-images',
    ];

    private AttributeRenderer $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/BeUsers.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileStorage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFile.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileMetadata.csv');

        $this->setUpBackendUser(1);

        $this->subject = $this->get(AttributeRenderer::class);
    }

    #[Test]
    public function givenPngFileExtensionForJpegImageWillResultInPngImage(): void
    {
        /** @var FileInterface $image */
        $image = GeneralUtility::makeInstance(FileRepository::class)->findByUid(1);
        $variant = new Variant('test', [
            'providedImageSizes.' => [
                '10.' => [
                    'width' => '200',
                ],
            ],
        ]);

        $srcset = $this->subject->renderSrcset($image, $variant, 'default', 'png');

        self::assertMatchesRegularExpression('/csm_test_[a-f0-9]{10}\.png/', $srcset);
    }
}
