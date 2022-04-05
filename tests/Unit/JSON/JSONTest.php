<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\JSON;

use Geniusee\MoneyHubSdk\Helpers\JSON;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class JSONTest extends TestCase
{
    public function testEncodeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        JSON::encode(INF);
    }

    public function testDecodeInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);
        JSON::decode('}');
    }

    public function testEncode(): void
    {
        self::assertSame(json_encode([1], JSON_THROW_ON_ERROR), JSON::encode([1]));
    }

    public function testDecode(): void
    {
        self::assertSame([], JSON::decode('{}'));
    }
}
