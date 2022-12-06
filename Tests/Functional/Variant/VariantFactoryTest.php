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
        $this->configurationToUseInTestInstance = [
            'FE' => [
                'defaultTypoScript_setup' => '@import \'EXT:responsive_images/Tests/Functional/Fixtures/TypoScript/\'',
            ],
        ];

        parent::setUp();

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
}
