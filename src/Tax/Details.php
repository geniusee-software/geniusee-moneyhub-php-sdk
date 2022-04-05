<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class Details
{
    /**
     * @psalm-param array{value:int, currency:string} $amount
     */
    public function __construct(private string $category, private array $amount)
    {
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getAmount(): Amount
    {
        return new Amount($this->amount['value'], $this->amount['currency']);
    }
}
