<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Categories;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;

final class CategoriesGroupCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-return array<array-key, CategoryGroup>
     */
    public function get(): array
    {
        return array_map(
            /**
             * @psalm-param array{id:string, key:string} $categoryGroup
             */
            static fn ($categoryGroup) => new CategoryGroup(
                $categoryGroup['id'],
                $categoryGroup['key'],
            ),
            $this->response['data']
        );
    }
}
