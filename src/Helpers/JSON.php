<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Helpers;

use InvalidArgumentException;
use Throwable;

final class JSON
{
    public static function encode(mixed $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new InvalidArgumentException('json_encode error: ' . $e->getMessage());
        }
    }

    public static function decode(string $json): mixed
    {
        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new InvalidArgumentException('json_decode error: ' . $e->getMessage());
        }
    }
}
