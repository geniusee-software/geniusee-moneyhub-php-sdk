<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Config;

use Webmozart\Assert\Assert;

/**
 * @internal
 */
final class PhpConfigParser implements ConfigParser
{
    public function __construct(private string $configFilePath)
    {
    }

    public function parse(): array
    {
        /**
         * @psalm-suppress UnresolvableInclude
         */
        $config = require $this->configFilePath;
        Assert::isArray($config);

        return $config;
    }
}
