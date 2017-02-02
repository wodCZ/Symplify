<?php declare(strict_types=1);

namespace Symplify\PHP7_CodeSniffer\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Symplify\PHP7_CodeSniffer\Validator\ValueNormalizer;

final class ValueNormalizerTest extends TestCase
{
    public function testNormalizeCommaSeparatedValues()
    {
        $valueNormalizer = new ValueNormalizer();
        $normalizedValues = $valueNormalizer->normalizeCommaSeparatedValues([
           'one,two'
        ]);

        $this->assertSame(['one', 'two'], $normalizedValues);
    }
}
