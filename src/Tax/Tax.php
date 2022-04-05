<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class Tax implements MoneyHubEntity
{
    private string $dateFrom;
    private string $dateTo;
    private TaxReturn $taxReturn;

    public function __construct(string $dateFrom, string $dateTo, TaxReturn $taxReturn)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->taxReturn = $taxReturn;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function getTaxReturn(): TaxReturn
    {
        return $this->taxReturn;
    }
}
