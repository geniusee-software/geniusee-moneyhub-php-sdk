<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Authorization;

use League\OAuth2\Client\Token\AccessTokenInterface;

interface Authorization
{
    public function getToken(): AccessTokenInterface;

    public function withScopes(array $scopes): self;

    public function withGrantType(string $grantType): self;

    public function withSubject(?string $subject = null): self;
}
