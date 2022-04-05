<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Authorization;

/**
 * @internal
 * @psalm-immutable
 */
final class DefaultConfig
{
    public const MONEY_HUB_CONFIG = [
        'idTokenIssuer' => 'https://identity.moneyhub.co.uk/oidc',
        'urlAuthorize' => 'https://identity.moneyhub.co.uk/oidc/auth',
        'urlApi' => 'https://api.moneyhub.co.uk/v2.0',
        'urlAccessToken' => 'https://identity.moneyhub.co.uk/oidc/token',
        'urlResourceOwnerDetails' => 'https://api-dev.moneyhub.co.uk/v2.0',
    ];
}
