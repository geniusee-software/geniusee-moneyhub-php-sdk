<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use DateTimeImmutable;
use Exception;

final class AccountsTransactionData
{
    public function __construct(private int $count, private string $earliestDate, private string $lastDate)
    {
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @throws Exception
     */
    public function getEarliestDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->earliestDate);
    }

    /**
     * @throws Exception
     */
    public function getLastDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->lastDate);
    }
}
