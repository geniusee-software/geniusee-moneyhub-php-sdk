<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class TaxReturn
{
    public function __construct(private array $SA105)
    {
    }

    public function getSA105(): SA105
    {
        /**
         * @psalm-var array{income:array, expenditure:array, taxableIncome:array} $sa105
         */
        $sa105 = $this->SA105['sa105'];

        return new SA105($sa105['income'], $sa105['expenditure'], $sa105['taxableIncome']);
    }
}
