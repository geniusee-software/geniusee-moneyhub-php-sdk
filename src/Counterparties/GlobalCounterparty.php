<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Counterparties;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class GlobalCounterparty implements MoneyHubEntity
{
    /**
     * @psalm-param array{name?:?string, code?:?string} $mcc
     */
    public function __construct(
        private string $id,
        private string $label,
        private string $companyName,
        private string $logo,
        private string $website,
        private array $mcc
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getMCC(): MCC
    {
        return new MCC($this->mcc['code'] ?? null, $this->mcc['name'] ?? null);
    }
}
