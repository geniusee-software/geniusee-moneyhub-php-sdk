<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Counterparties;

final class MCC
{
    public function __construct(private ?string $code = null, private ?string $name = null)
    {
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
