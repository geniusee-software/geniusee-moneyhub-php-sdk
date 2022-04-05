<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

final class TransactionCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument, MixedArrayAccess
     * @psalm-return array<array-key, Transaction>
     */
    public function get(): array
    {
        /**
         * @psalm-suppress MixedAssignment
         */
        $transactions = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            static fn (array $transaction) => new Transaction(
                $transaction['amount'],
                $transaction['categoryId'],
                $transaction['categoryIdConfirmed'],
                $transaction['date'],
                $transaction['dateModified'],
                $transaction['id'],
                $transaction['longDescription'],
                $transaction['notes'],
                $transaction['shortDescription'],
                $transaction['status'],
                $transaction['accountId'] ?? null,
                $transaction['providerId'] ?? null,
                $transaction['counterpartyId'] ?? null,
                $transaction['projectId'] ?? null,
                $transaction['enhancedCategories'] ?? null,
                $transaction['splits'] ?? [],
                $transaction['transactionCode'] ?? null,
                $transaction['proprietaryTransactionCode'] ?? null,
                $transaction['balance'] ?? null,
                $transaction['balanceType'] ?? null,
                $transaction['statementReference'] ?? null,
                $transaction['merchantName'] ?? null,
                $transaction['merchantCategoryCode'] ?? null,
                $transaction['cardInstrument'] ?? null,
                $transaction['creditorAccount'] ?? null,
                $transaction['creditorAgent'] ?? null,
                $transaction['debtorAccount'] ?? null,
                $transaction['debtorAgent'] ?? null,
            ),
            $transactions
        );
    }
}
