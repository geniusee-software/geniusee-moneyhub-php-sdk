<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Sync;

final class SyncDto
{
    public function __construct(private string $status)
    {
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
