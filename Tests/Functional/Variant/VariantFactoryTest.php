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
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\AstBuilder;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptStringFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \Codemonkey1988\ResponsiveImages\Variant\VariantFactory
 */
class VariantFactoryTest extends FunctionalTestCase
{
    private VariantFactory $subject;

    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'typo3conf/ext/responsive_images'
        ];

        parent::setUp();

        if (version_compare(VersionNumberUtility::getCurrentTypo3Version(), '12.1.0', '>=')) {
            $this->initializeTypoScriptV12();
        } else {
            $this->initializeTypoScript();
        }

        $this->subject = $this->get(VariantFactory::class);
    }

    /**
     * @test
     */
    public function createNewInstanceAndReceiveDefaultConfiguration(): void
    {
        $variant = $this->subject->get();

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame('default', $variant->getConfig()['croppingVariantKey'] ?? '');
    }

    /**
     * @test
     */
    public function createNewInstanceAndReceiveGivenConfiguration(): void
    {
        $variant = $this->subject->get('large');

        self::assertInstanceOf(Variant::class, $variant);
        self::assertSame('large', $variant->getConfig()['croppingVariantKey'] ?? '');
    }

    /**
     * @test
     */
    public function createNewInstanceAndThrowNoSuchVariantException(): void
    {
        $this->expectException(NoSuchVariantException::class);
        $this->expectExceptionCode(1623538021);

        $this->subject->get('does-not-exist');
    }

    /**
     * @test
     */
    public function hasVariantReturnsTrue(): void
    {
        self::assertTrue($this->subject->has('large'));
    }

    /**
     * @test
     */
    public function hasVariantReturnsFalse(): void
    {
        self::assertFalse($this->subject->has('does-not-exist'));
    }

    /**
     * @test
     */
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
        $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoScriptParser->parse($typoScript);

        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $GLOBALS['TSFE']->tmpl->setup = $typoScriptParser->setup;

        $request = new ServerRequest();
        $GLOBALS['TYPO3_REQUEST'] = $request
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);

        // Required for TYPO3 v10
        $environmentService = GeneralUtility::makeInstance(EnvironmentService::class);
        $environmentService->setFrontendMode(true);
    }

    private function initializeTypoScriptV12(): void
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
