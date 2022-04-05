<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use Money\Currency;
use Money\Money;

/**
 * @psalm-param array{value:int, currency:string} $amount
 */
final class AccountBalanceAmount
{
    /**
     * @psalm-param non-empty-string $currency
     */
    public function __construct(private int $value, private string $currency)
    {
    }

    public function getValue(): Money
    {
        return new Money($this->value, new Currency($this->currency));
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
