<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use DateTimeImmutable;
use DomainException;
use Exception;

/**
 * @psalm-immutable
 */
final class AccountBalance
{
    /**
     * @psalm-param array{value:int, currency:string} $amount
     */
    public function __construct(private array $amount, private string $date)
    {
    }

    public function getAmount(): AccountBalanceAmount
    {
        if ($this->amount['currency'] === '') {
            throw new DomainException('Currency can not be empty');
        }

        return new AccountBalanceAmount($this->amount['value'], $this->amount['currency']);
    }

    /**
     * @throws Exception
     */
    public function getDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->date);
    }
}
