<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Authorization;

/**
 * @psalm-immutable
 */
final class AuthParams
{
    public const GRANT_AUTHORIZATION_CODE = 'authorization_code';
    public const GRANT_CLIENT_CREDENTIALS = 'client_credentials';
    public const GRANT_REFRESH_TOKEN = 'refresh_token';

    public const AUTH_BASIC_PARAM = 'client_secret_basic';
}
