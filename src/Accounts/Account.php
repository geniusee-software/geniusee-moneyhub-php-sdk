<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use DateTimeImmutable;
use Exception;
use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

/**
 * @psalm-template Balance as array{amount:array{value:int, currency:string}, date:string}
 * @psalm-template AccountDetailsT as array{
 *    AER?:?int,
 *    APR?:?int,
 *    sortCodeAccountNumber?:?string,
 *    iban?:?string,
 *    pan?:?string,
 *    creditLimit?:?int,
 *    endDate?:?string,
 *    fixedDate?:?string,
 *    interestFreePeriod?:?int,
 *    interestType?:?string,
 *    linkedProperty?:?string,
 *    monthlyRepayment?:?int,
 *    overdraftLimit?: ?int,
 *    postcode?:?string,
 *    runningCost?:?integer,
 *    runningCostPeriod?:?string,
 *    term?:?int,
 *    yearlyAppreciation?:?int
 * }
 */
final class Account implements MoneyHubEntity
{
    /**
     * @psalm-param Balance $balance
     * @psalm-param AccountDetailsT $accountDetails
     * @psalm-param array{count:int, earliestDate:string, lastDate:string} $transactionData
     */
    public function __construct(
        private string $accountName,
        private array $balance,
        private array $accountDetails,
        private string $dateAdded,
        private string $dateModified,
        private string $id,
        private string $type,
        private ?string $currency = null,
        private ?array $transactionData = null,
        private ?string $providerName = null,
        private ?string $providerReference = null,
        private ?string $connectionId = null,
        private ?string $providerId = null,
        private ?string $accountReference = null,
        private ?string $accountHolderName = null,
        private ?string $accountType = null,
    ) {
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getBalance(): AccountBalance
    {
        return new AccountBalance($this->balance['amount'], $this->balance['date']);
    }

    public function getAccountDetails(): AccountDetails
    {
        /**
         * @psalm-suppress MixedArgument
         */
        return new AccountDetails(
            $this->accountDetails['AER'] ?? null,
            $this->accountDetails['APR'] ?? null,
            $this->accountDetails['sortCodeAccountNumber'] ?? null,
            $this->accountDetails['iban'] ?? null,
            $this->accountDetails['pan'] ?? null,
            $this->accountDetails['creditLimit'] ?? null,
            $this->accountDetails['endDate'] ?? null,
            $this->accountDetails['fixedDate'] ?? null,
            $this->accountDetails['interestFreePeriod'] ?? null,
            $this->accountDetails['interestType'] ?? null,
            $this->accountDetails['linkedProperty'] ?? null,
            $this->accountDetails['monthlyRepayment'] ?? null,
            $this->accountDetails['overdraftLimit'] ?? null,
            $this->accountDetails['postcode'] ?? null,
            $this->accountDetails['runningCost'] ?? null,
            $this->accountDetails['runningCostPeriod'] ?? null,
            $this->accountDetails['term'] ?? null,
            $this->accountDetails['yearlyAppreciation'] ?? null,
        );
    }

    /**
     * @throws Exception
     */
    public function getDateAdded(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->dateAdded);
    }

    /**
     * @throws Exception
     */
    public function getDateModified(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->dateModified);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getTransactionData(): ?AccountsTransactionData
    {
        if ($this->transactionData === null) {
            return null;
        }

        return new AccountsTransactionData(
            $this->transactionData['count'],
            $this->transactionData['earliestDate'],
            $this->transactionData['lastDate']
        );
    }

    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    public function getProviderReference(): ?string
    {
        return $this->providerReference;
    }

    public function getConnectionId(): ?string
    {
        return $this->connectionId;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function getAccountReference(): ?string
    {
        return $this->accountReference;
    }

    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }
}
