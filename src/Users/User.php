<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Users;

use DateTimeImmutable;
use Exception;

/**
 * @psalm-immutable
 */
final class User
{
    public function __construct(
        private string $userId,
        private string $clientUserId,
        private string $clientId,
        private string $createdAt,
        private string $updatedAt,
        private string $scopes,
        private string $managedBy,
        private string $lastAccessed,
        private string $userType,
        private array $connectionIds,
        private ?string $deletedAt = null,
        private ?string $clientName = null,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getClientUserId(): string
    {
        return $this->clientUserId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @throws Exception
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->createdAt);
    }

    /**
     * @throws Exception
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->updatedAt);
    }

    public function getScopes(): string
    {
        return $this->scopes;
    }

    public function getManagedBy(): string
    {
        return $this->managedBy;
    }

    public function getLastAccessed(): string
    {
        return $this->lastAccessed;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getConnectionIds(): array
    {
        return $this->connectionIds;
    }

    /**
     * @throws Exception
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt === null ? null : new DateTimeImmutable($this->deletedAt);
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }
}
