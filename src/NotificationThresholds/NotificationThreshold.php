<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\NotificationThresholds;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class NotificationThreshold implements MoneyHubEntity
{
    public function __construct(private string $type, private ?int $value = null, private ?string $id = null)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
