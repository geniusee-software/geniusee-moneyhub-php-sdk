<?php

declare(strict_types=1);

namespace Geniusee\Tests\_fakes;

use Geniusee\MoneyHubSdk\Authorization\Authorization;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * @internal
 */
final class AuthorizationFake implements Authorization
{
    public function getToken(): AccessTokenInterface
    {
        return new AccessToken(
            [
                'access_token' => 'XdUtqZW5HWlJUbnJpeUxXRnZuS2tzTjNvLWFu',
                'expires_in' => 600,
                'token_type' => 'Bearer',
            ]
        );
    }

    public function withScopes(array $scopes): self
    {
        return $this;
    }

    public function withGrantType(string $grantType): self
    {
        return $this;
    }

    public function withSubject(?string $subject = null): self
    {
        return $this;
    }
}
