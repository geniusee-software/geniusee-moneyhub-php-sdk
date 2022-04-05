<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Config;

interface ConfigParser
{
    public function parse(): array;
}
