<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class CreditorAgent
{
    public function __construct(private ?string $name, private ?array $postalAddress)
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPostalAddress(): ?array
    {
        return $this->postalAddress;
    }
}
