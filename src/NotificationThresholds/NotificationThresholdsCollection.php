<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\NotificationThresholds;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

final class NotificationThresholdsCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-return array<array-key, NotificationThreshold>
     */
    public function get(): array
    {
        /**
         * @psalm-var array $thresholds
         */
        $thresholds = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            /**
             * @psalm-param array{type:string, value?:?int, id?:?string} $threshold
             */
            static fn ($threshold) => new NotificationThreshold(
                $threshold['type'],
                $threshold['value'] ?? null,
                $threshold['id'] ?? null,
            ),
            $thresholds
        );
    }
}
