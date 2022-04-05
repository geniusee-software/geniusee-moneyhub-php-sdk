<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Authorization;

use Geniusee\MoneyHubSdk\Exception\ConfigException;
use GuzzleHttp\Client;

/**
 * @internal
 */
final class AuthorizationStrategy
{
    public function __construct(private array $config)
    {
    }

    /**
     * @throws ConfigException
     */
    public function create(): Authorization
    {
        return match ($this->config['token_endpoint_auth_method']) {
            AuthParams::AUTH_BASIC_PARAM => new BasicAuth($this->config, new Client()),
            default => throw new ConfigException(
                sprintf(
                    'Forbidden token_endpoint_auth_method %s',
                    (string)$this->config['token_endpoint_auth_method']
                )
            )
        };
    }
}
