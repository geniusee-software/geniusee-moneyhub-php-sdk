<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class Amount
{
    public function __construct(private int $value, private string $currency)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
