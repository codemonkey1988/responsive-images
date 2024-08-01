<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Variant;

use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\AstBuilder;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\TypoScript\TypoScriptStringFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(VariantFactory::class)]
class VariantFactoryTest extends FunctionalTestCase
{
    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'codemonkey1988/responsive-images',
    ];

    private VariantFactory $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initializeTypoScript();

        $this->subject = $this->get(VariantFactory::class);
    }

    #[Test]
    public function createNewInstanceAndReceiveDefaultConfiguration(): void
    {
        $variant = $this->subject->get();

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame('default', $variant->getConfig()['croppingVariantKey'] ?? '');
    }

    #[Test]
    public function createNewInstanceAndReceiveGivenConfiguration(): void
    {
        $variant = $this->subject->get('large');

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame('large', $variant->getConfig()['croppingVariantKey'] ?? '');
    }

    #[Test]
    public function createNewInstanceAndThrowNoSuchVariantException(): void
    {
        $this->expectException(NoSuchVariantException::class);
        $this->expectExceptionCode(1623538021);

        $this->subject->get('does-not-exist');
    }

    #[Test]
    public function hasVariantReturnsTrue(): void
    {
        self::assertTrue($this->subject->has('large'));
    }

    #[Test]
    public function hasVariantReturnsFalse(): void
    {
        self::assertFalse($this->subject->has('does-not-exist'));
    }

    #[Test]
    public function getVariantKeyUseFromRegistry(): void
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->register['IMAGE_VARIANT_KEY'] = 'large';

        $variant = $this->subject->get();

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame('large', $variant->getConfig()['croppingVariantKey'] ?? '');
    }

    private function initializeTypoScript(): void
    {
        $typoScript = (string)file_get_contents(__DIR__ . '/../Fixtures/TypoScript/TestingVariants.typoscript');
        $typoScriptFactory = GeneralUtility::makeInstance(TypoScriptStringFactory::class);
        $astBuilder = GeneralUtility::makeInstance(AstBuilder::class);
        $rootNode = $typoScriptFactory->parseFromString($typoScript, $astBuilder);

        $frontendTypoScript = new FrontendTypoScript($rootNode, []);
        $frontendTypoScript->setSetupArray($rootNode->toArray());

        $request = new ServerRequest();
        $GLOBALS['TYPO3_REQUEST'] = $request
            ->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
    }
}
