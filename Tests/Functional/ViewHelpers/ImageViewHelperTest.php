<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\ViewHelpers;

use Codemonkey1988\ResponsiveImages\Tests\Functional\ServerRequestTrait;
use Codemonkey1988\ResponsiveImages\ViewHelpers\ImageViewHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Fluid\View\TemplateView;

#[CoversClass(ImageViewHelper::class)]
class ImageViewHelperTest extends ViewHelperTestCase
{
    use ServerRequestTrait;

    /**
     * @var array<string, mixed>
     */
    protected array $configurationToUseInTestInstance = [
        'FE' => [
            'defaultTypoScript_setup' => '@import \'EXT:responsive_images/Tests/Functional/Fixtures/TypoScript/\'',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/BeUsers.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileStorage.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFile.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/SysFileMetadata.csv');

        $this->setUpBackendUser(1);

        $GLOBALS['TYPO3_REQUEST'] = $this->buildFakeServerRequest();
    }

    #[Test]
    public function missingSrcsetVariantWillRenderDefaultImgTag(): void
    {
        $image =  $this->get(FileRepository::class)->findByUid(1);
        $template = '<r:image image="{image}" srcsetVariantKey="does-not-exist" />';
        $context = $this->buildRenderingContext();
        $context->getVariableProvider()->add('image', $image);
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        $renderedTag = $templateView->render();
        $expected = '<img src="typo3conf/ext/responsive_images/Tests/Functional/Fixtures/Storage/test.jpg" ' .
            'width="1920" height="1080" alt="" />';

        self::assertSame($expected, $renderedTag);
    }

    #[Test]
    public function givenSrcsetVariantKeyWillRenderImgTagWithSrcsetAndSizesAttribute(): void
    {
        $image =  $this->get(FileRepository::class)->findByUid(1);
        $template = '<r:image image="{image}" srcsetVariantKey="default" />';
        $context = $this->buildRenderingContext();
        $context->getVariableProvider()->add('image', $image);
        $context->getTemplatePaths()->setTemplateSource($template);
        $templateView = new TemplateView($context);

        $renderedTag = $templateView->render();
        $expected = '<img src="typo3conf/ext/responsive_images/Tests/Functional/Fixtures/Storage/test.jpg" ' .
            'width="1920" height="1080" alt="" ' .
            'srcset="typo3conf/ext/responsive_images/Tests/Functional/Fixtures/Storage/_processed_/e/8/csm_test_750b6486c1.jpg 1000w" ' .
            'sizes="(min-width: 971px) 585px, (min-width: 751px) 485px, (min-width: 421px) 375px, 420px" />';

        self::assertSame($expected, $renderedTag);
    }
}
