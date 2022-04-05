<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

final class SA105
{
    /**
     * @psalm-param array {total:array, details:array} $income
     * @psalm-param array {total:array{value:int, currency:string}} $taxableIncome
     */
    public function __construct(
        private array $income,
        private array $expenditure,
        private array $taxableIncome
    ) {
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function getIncome(): Income
    {
        $details = array_map(
            /**
             * @psalm-param array{
             *     category:string,
             *     amount:array{value:int, currency:string},
             *     } $detail
             */
            static fn ($detail) => new Details(
                $detail['category'],
                $detail['amount'],
            ),
            $this->income['details']
        );

        return new Income($this->income['total'], $details);
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function getExpenditure(): Expenditure
    {
        $details = array_map(
            /**
             * @psalm-param array{
             *     category:string,
             *     amount:array{value:int, currency:string},
             *     } $detail
             */
            static fn ($detail) => new Details(
                $detail['category'],
                $detail['amount'],
            ),
            $this->expenditure['details']
        );

        return new Expenditure($this->income['total'], $details);
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function getTaxableIncome(): TaxableIncome
    {
        return new TaxableIncome($this->taxableIncome['total']);
    }
}
