<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class EnhancedCategories
{
    public function __construct(private ?string $ukTaxHmrc = null)
    {
    }

    public function getUkTaxHmrc(): ?string
    {
        return $this->ukTaxHmrc;
    }
}
