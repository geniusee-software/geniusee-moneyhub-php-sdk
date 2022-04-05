<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Config;

use Geniusee\MoneyHubSdk\Exception\ConfigException;

/**
 * @internal
 */
final class ConfigStrategy
{
    /**
     * @throws ConfigException
     */
    public static function getConfigParser(string $configPath): ConfigParser
    {
        $getPathExtension = pathinfo($configPath, PATHINFO_EXTENSION);

        return match ($getPathExtension) {
            'php' => new PhpConfigParser($configPath),
            'json' => new JsonConfigParser($configPath),
            default => throw new ConfigException(
                sprintf(
                    'Forbidden from for config file %s',
                    $getPathExtension
                )
            )
        };
    }
}
