<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

use Geniusee\MoneyHubSdk\Entity\MoneyHubEntity;

final class TransactionAttachment implements MoneyHubEntity
{
    public function __construct(
        private string $id,
        private string $fileType,
        private string $fileName,
        private string $url
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFileType(): string
    {
        return $this->fileType;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
