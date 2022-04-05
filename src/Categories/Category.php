<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Categories;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class Category implements MoneyHubEntity
{
    public function __construct(
        private string $categoryId,
        private string $group,
        private ?string $name = null,
        private ?string $key = null
    ) {
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }
}
