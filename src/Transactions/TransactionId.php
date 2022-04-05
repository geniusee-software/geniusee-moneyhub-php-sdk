<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class TransactionId
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
