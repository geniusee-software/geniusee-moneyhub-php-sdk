<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class TransactionSplit
{
    public function __construct(
        private int $amount,
        private string $categoryId,
        private string $description,
        private string $id,
        private ?string $projectId = null
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }
}
