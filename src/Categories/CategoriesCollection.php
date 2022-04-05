<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Categories;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

final class CategoriesCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     */
    public function get(): array
    {
        /**
         * @psalm-var array $categories
         */
        $categories = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            /**
             * @psalm-param array{categoryId:string, group:string, name?:?string, key?:?string} $category
             */
            static fn ($category) => new Category(
                $category['categoryId'],
                $category['group'],
                $category['name'] ?? null,
                $category['key'] ?? null,
            ),
            $categories
        );
    }
}
