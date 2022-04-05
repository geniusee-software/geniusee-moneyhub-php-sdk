<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class TransactionCode
{
    public function __construct(private string $code, private string $subCode)
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSubCode(): string
    {
        return $this->subCode;
    }
}
