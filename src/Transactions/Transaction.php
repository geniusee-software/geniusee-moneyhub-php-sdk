<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class Transaction implements MoneyHubEntity
{
    /**
     * @psalm-param array{value:int,currency:string} $amount
     * @psalm-param array{uk-tax-hmrc:string|null} $enhancedCategories
     * @psalm-param array{code:string, subCode:string} $transactionCode
     * @psalm-param array{code:string, issuer?:?string} $proprietaryTransactionCode
     * @psalm-param array{name:string, pan:string, cardSchemeName:string, authorisationType:string} $cardInstrument
     * @psalm-param array{name:string, sortCode:string, accountNumber:string, iban:string, pan:string} $creditorAccount
     * @psalm-param array{name:string, sortCode:string, iban:string, pan:string, accountNumber:string} $debtorAccount
     * @psalm-param array{name:string, postalAddress:array} $debtorAgent
     * @psalm-param array{name:string, postalAddress:array} $creditorAgent
     */
    public function __construct(
        private array $amount,
        private string $categoryId,
        private bool $categoryIdConfirmed,
        private string $date,
        private string $dateModified,
        private string $id,
        private string $longDescription,
        private string $notes,
        private string $shortDescription,
        private string $status,
        private ?string $accountId = null,
        private ?string $providerId = null,
        private ?string $counterpartyId = null,
        private ?string $projectId = null,
        private ?array $enhancedCategories = null,
        private array $splits = [],
        private ?array $transactionCode = null,
        private ?array $proprietaryTransactionCode = null,
        private ?float $balance = null,
        private ?string $balanceType = null,
        private ?string $statementReference = null,
        private ?string $merchantName = null,
        private ?string $merchantCategoryCode = null,
        private ?array $cardInstrument = null,
        private ?array $creditorAccount = null,
        private ?array $creditorAgent = null,
        private ?array $debtorAccount = null,
        private ?array $debtorAgent = null
    ) {
    }

    public function getCreditorAccount(): CreditorAccount
    {
        return new CreditorAccount(
            $this->creditorAccount['name'] ?? null,
            $this->creditorAccount['sortCode'] ?? null,
            $this->creditorAccount['accountNumber'] ?? null,
            $this->creditorAccount['iban'] ?? null,
            $this->creditorAccount['pan'] ?? null,
        );
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getBalanceType(): ?string
    {
        return $this->balanceType;
    }

    public function getStatementReference(): ?string
    {
        return $this->statementReference;
    }

    public function getMerchantName(): ?string
    {
        return $this->merchantName;
    }

    public function getMerchantCategoryCode(): ?string
    {
        return $this->merchantCategoryCode;
    }

    public function getCardInstrument(): CardInstrument
    {
        return new CardInstrument(
            $this->cardInstrument['name'] ?? null,
            $this->cardInstrument['pan'] ?? null,
            $this->cardInstrument['cardSchemeName'] ?? null,
            $this->cardInstrument['authorisationType'] ?? null,
        );
    }

    public function getAmount(): Amount
    {
        return new Amount($this->amount['value'], $this->amount['currency']);
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function isCategoryIdConfirmed(): bool
    {
        return $this->categoryIdConfirmed;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDateModified(): string
    {
        return $this->dateModified;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLongDescription(): string
    {
        return $this->longDescription;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function getCounterpartyId(): ?string
    {
        return $this->counterpartyId;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    public function getEnhancedCategories(): EnhancedCategories
    {
        return new EnhancedCategories($this->enhancedCategories['uk-tax-hmrc'] ?? null);
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function getSplits(): array
    {
        return array_map(static fn (array $split) => new Split(
            /**
             * @psalm-param array{amount:string,categoryId:string,description:string,id:string,projectId:string|null} $split
             */
            $split['amount'],
            $split['categoryId'],
            $split['description'],
            $split['id'],
            $split['projectId'] ?? null
        ), $this->splits);
    }

    public function getTransactionCode(): ?TransactionCode
    {
        if ($this->transactionCode === null) {
            return null;
        }

        return new TransactionCode($this->transactionCode['code'], $this->transactionCode['subCode']);
    }

    public function getProprietaryTransactionCode(): ?ProprietaryTransactionCode
    {
        if ($this->proprietaryTransactionCode === null) {
            return null;
        }

        return new ProprietaryTransactionCode(
            $this->proprietaryTransactionCode['code'],
            $this->proprietaryTransactionCode['issuer'] ?? null
        );
    }

    public function getCreditorAgent(): CreditorAgent
    {
        return new CreditorAgent(
            $this->creditorAgent['name'] ?? null,
            $this->creditorAgent['postalAddress'] ?? null
        );
    }

    public function getDebtorAccount(): DebtorAccount
    {
        return new DebtorAccount(
            $this->debtorAccount['name'] ?? null,
            $this->debtorAccount['sortCode'] ?? null,
            $this->debtorAccount['accountNumber'] ?? null,
            $this->debtorAccount['iban'] ?? null,
            $this->debtorAccount['pan'] ?? null
        );
    }

    public function getDebtorAgent(): DebtorAgent
    {
        return new DebtorAgent(
            $this->debtorAgent['name'] ?? null,
            $this->debtorAgent['postalAddress'] ?? null
        );
    }
}
