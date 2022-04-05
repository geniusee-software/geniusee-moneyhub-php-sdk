<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Config;

use Geniusee\MoneyHubSdk\Helpers\JSON;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class JsonConfigParser implements ConfigParser
{
    public function __construct(private string $configFilePath)
    {
    }

    public function parse(): array
    {
        $config = JSON::decode(file_get_contents($this->configFilePath));
        Assert::isArray($config);

        return $config;
    }
}
