<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class ProprietaryTransactionCode
{
    public function __construct(private string $code, private ?string $issuer = null)
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }
}
