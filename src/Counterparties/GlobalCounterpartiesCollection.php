<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Counterparties;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;

final class GlobalCounterpartiesCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-return array<mixed, GlobalCounterparty>
     */
    public function get(): array
    {
        return array_map(
            /**
             * @psalm-param array{
             *     id:string,
             *     label:string,
             *     companyName:string,
             *     logo:string,
             *     website:string,
             *     mcc: array{name?:?string, code?:?string}
             *     } $counterparty
             */
            static fn (array $counterparty) => new GlobalCounterparty(
                $counterparty['id'],
                $counterparty['label'],
                $counterparty['companyName'],
                $counterparty['logo'],
                $counterparty['website'],
                $counterparty['mcc']
            ),
            $this->response()['data']
        );
    }
}
