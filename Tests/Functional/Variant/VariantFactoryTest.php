<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional\Variant;

use Codemonkey1988\ResponsiveImages\Tests\Functional\ServerRequestTrait;
use Codemonkey1988\ResponsiveImages\Variant\Exception\NoSuchVariantException;
use Codemonkey1988\ResponsiveImages\Variant\Variant;
use Codemonkey1988\ResponsiveImages\Variant\VariantFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

#[CoversClass(VariantFactory::class)]
class VariantFactoryTest extends FunctionalTestCase
{
    use ServerRequestTrait;

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

        $GLOBALS['TYPO3_REQUEST'] = $this->buildFakeServerRequest();

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
}
