<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class CreditorAccount
{
    public function __construct(
        private ?string $name,
        private ?string $sortCode,
        private ?string $accountNumber,
        private ?string $iban,
        private ?string $pan
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSortCode(): ?string
    {
        return $this->sortCode;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function getPan(): ?string
    {
        return $this->pan;
    }
}
