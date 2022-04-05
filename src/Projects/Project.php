<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Projects;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class Project implements MoneyHubEntity
{
    public function __construct(
        private string $id,
        private string $name,
        private array $accountsIds,
        private string $type,
        private ?string $dateCreated = null,
        private ?bool $archived = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAccountsIds(): array
    {
        return $this->accountsIds;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }
}
