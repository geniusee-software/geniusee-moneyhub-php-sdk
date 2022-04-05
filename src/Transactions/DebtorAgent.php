<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class DebtorAgent
{
    public function __construct(private ?string $name, private ?array $postalAddress)
    {
    }

    public function getPostalAddress(): ?array
    {
        return $this->postalAddress;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
