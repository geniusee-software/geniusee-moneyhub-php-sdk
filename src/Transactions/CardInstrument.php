<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

final class CardInstrument
{
    public function __construct(
        private ?string $name,
        private ?string $pan,
        private ?string $cardSchemeName,
        private ?string $authorisationType
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPan(): ?string
    {
        return $this->pan;
    }

    public function getCardSchemeName(): ?string
    {
        return $this->cardSchemeName;
    }

    public function getAuthorisationType(): ?string
    {
        return $this->authorisationType;
    }
}
