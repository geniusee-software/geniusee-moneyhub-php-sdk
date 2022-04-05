<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Categories;

final class CategoryGroup
{
    public function __construct(private string $id, private string $key)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
