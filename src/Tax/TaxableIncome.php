<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class TaxableIncome
{
    /**
     * @psalm-param array{value:int, currency:string} $total
     */
    public function __construct(private array $total)
    {
    }

    public function getTotal(): Total
    {
        return new Total($this->total['value'], $this->total['currency']);
    }
}
