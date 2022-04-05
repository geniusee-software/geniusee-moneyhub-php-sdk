<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

/**
 * @psalm-suppress MixedArgument
 * @psalm-suppress MixedAssignment
 */
final class AccountBalanceCollection extends AbstractCollection
{
    /**
     * @psalm-return array<mixed, AccountBalance>
     */
    public function get(): array
    {
        $accountData = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            static fn (array $account) => new AccountBalance($account['amount'], $account['date']),
            $accountData
        );
    }
}
