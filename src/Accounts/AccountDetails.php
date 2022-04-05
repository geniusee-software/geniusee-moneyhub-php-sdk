<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

/**
 * @psalm-immutable
 */
final class AccountDetails
{
    public function __construct(
        private ?int $AER = null,
        private ?int $APR = null,
        private ?string $sortCodeAccountNumber = null,
        private ?string $iban = null,
        private ?string $pan = null,
        private ?int $creditLimit = null,
        private ?string $endDate = null,
        private ?string $fixedDate = null,
        private ?int $interestFreePeriod = null,
        private ?string $interestType = null,
        private ?string $linkedProperty = null,
        private ?int $monthlyRepayment = null,
        private ?int $overdraftLimit = null,
        private ?string $postcode = null,
        private ?int $runningCost = null,
        private ?string $runningCostPeriod = null,
        private ?int $term = null,
        private ?int $yearlyAppreciation = null,
    ) {
    }

    public function getAER(): ?int
    {
        return $this->AER;
    }

    public function getAPR(): ?int
    {
        return $this->APR;
    }

    public function getSortCodeAccountNumber(): ?string
    {
        return $this->sortCodeAccountNumber;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function getPan(): ?string
    {
        return $this->pan;
    }

    public function getCreditLimit(): ?int
    {
        return $this->creditLimit;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function getFixedDate(): ?string
    {
        return $this->fixedDate;
    }

    public function getInterestFreePeriod(): ?int
    {
        return $this->interestFreePeriod;
    }

    public function getInterestType(): ?string
    {
        return $this->interestType;
    }

    public function getLinkedProperty(): ?string
    {
        return $this->linkedProperty;
    }

    public function getMonthlyRepayment(): ?int
    {
        return $this->monthlyRepayment;
    }

    public function getOverdraftLimit(): ?int
    {
        return $this->overdraftLimit;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getRunningCost(): ?int
    {
        return $this->runningCost;
    }

    public function getRunningCostPeriod(): ?string
    {
        return $this->runningCostPeriod;
    }

    public function getTerm(): ?int
    {
        return $this->term;
    }

    public function getYearlyAppreciation(): ?int
    {
        return $this->yearlyAppreciation;
    }
}
