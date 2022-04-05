<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\AuthorizationTest;

use Geniusee\MoneyHubSdk\Authorization\AuthorizationStrategy;
use Geniusee\MoneyHubSdk\Authorization\BasicAuth;
use Geniusee\MoneyHubSdk\Exception\ConfigException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AuthorizationFactoryTest extends TestCase
{
    public function testCreateException(): void
    {
        $this->expectException(ConfigException::class);
        (new AuthorizationStrategy(['token_endpoint_auth_method' => 'unreal_method']))->create();
    }

    public function testBasicAuth(): void
    {
        $result = (new AuthorizationStrategy(
            [
                'token_endpoint_auth_method' => 'client_secret_basic',
                'clientId' => 'client_id',
                'clientSecret' => 'client_secret',
            ]
        ))->create();

        self::assertInstanceOf(BasicAuth::class, $result);
    }
}
