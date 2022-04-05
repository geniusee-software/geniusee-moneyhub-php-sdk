<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

/**
 * @psalm-template AccountT as array{
 *    array{
 *    accountName:string,
 *    dateAdded:string,
 *    dateModified:string,
 *    type:string,
 *    id:string,
 *    currency:?string,
 *    providerName?:string,
 *    providerReference?:string,
 *    connectionId?:string,
 *    providerId?:string,
 *    accountReference?:string,
 *    accountHolderName?:string,
 *    accountType?:string,
 *    balance: array{amount:array{currency:string, value:int}, date:string},
 *    transactionData?: array{count:int, earliestDate:string, lastDate:string},
 *    details: array{
 *    AER:?int,
 *    APR:?int,
 *    sortCodeAccountNumber:?string,
 *    iban:?string,
 *    pan:?string,
 *    creditLimit:?int,
 *    endDate:?string,
 *    fixedDate:?string,
 *    interestFreePeriod:?int,
 *    interestType:?string,
 *    linkedProperty:?string,
 *    monthlyRepayment:?int,
 *    overdraftLimit: ?int,
 *    postcode: ?string,
 *    runningCost: ?integer,
 *    runningCostPeriod:?string,
 *    term:?int,
 *    yearlyAppreciation:?int
 * }
 * }
 * }
 */
final class AccountsCollection extends AbstractCollection
{
    /**
     * @psalm-return array<mixed, Account>
     */
    public function get(): array
    {
        /**
         * @psalm-suppress MixedArgument
         * @psalm-var  AccountT $accounts
         */
        $accounts = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(static fn ($account) => new Account(
            $account['accountName'],
            $account['balance'],
            $account['details'],
            $account['dateAdded'],
            $account['dateModified'],
            $account['id'],
            $account['type'],
            $account['currency'] ?? null,
            $account['transactionData'] ?? null,
            $account['providerName'] ?? null,
            $account['providerReference'] ?? null,
            $account['connectionId'] ?? null,
            $account['providerId'] ?? null,
            $account['accountReference'] ?? null,
            $account['accountHolderName'] ?? null,
            $account['accountType'] ?? null,
        ), $accounts);
    }
}
