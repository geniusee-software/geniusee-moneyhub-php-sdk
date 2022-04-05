<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;

final class TaxesCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-return array<array-key, Tax>
     */
    public function get(): array
    {
        return array_map(
            /**
             * @psalm-param array{
             *     dateFrom:string,
             *     dateTo:string,
             *     taxReturn:array
             *     } $tax
             */
            static fn ($tax) => new Tax(
                $tax['dateFrom'],
                $tax['dateTo'],
                new TaxReturn($tax['taxReturn']),
            ),
            [$this->response['data']]
        );
    }
}
