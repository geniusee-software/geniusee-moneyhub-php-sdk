<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class Expenditure
{
    /**
     * @psalm-param array{value:int, currency:string} $total
     * @psalm-param array<array-key, Details> $details
     */
    public function __construct(private array $total, private array $details)
    {
    }

    public function getTotal(): Total
    {
        return new Total($this->total['value'], $this->total['currency']);
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
