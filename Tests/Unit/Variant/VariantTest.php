<?php

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Unit\Variant;

use Codemonkey1988\ResponsiveImages\Variant\Variant;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(Variant::class)]
class VariantTest extends UnitTestCase
{
    #[Test]
    public function createNewInstanceWithArguments(): void
    {
        $variant = new Variant('test', ['foo' => 'bar']);
        self::assertSame('test', $variant->getKey());
        self::assertSame(['foo' => 'bar'], $variant->getConfig());
    }
}
