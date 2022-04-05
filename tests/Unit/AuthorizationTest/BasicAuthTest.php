<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\AuthorizationTest;

use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Authorization\BasicAuth;
use Geniusee\MoneyHubSdk\Exception\EmptyScopesException;
use Geniusee\MoneyHubSdk\Users\Users;
use Geniusee\Tests\_fakes\HttpClientFake;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionObject;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BasicAuthTest extends TestCase
{
    private BasicAuth $basicAuth;

    protected function setUp(): void
    {
        $config = [
            'token_endpoint_auth_method' => 'client_secret_basic',
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'idTokenIssuer' => 'https://identity.moneyhub.co.uk/oidc',
            'urlAuthorize' => 'https://identity.moneyhub.co.uk/oidc/auth',
            'urlApi' => 'https://api.moneyhub.co.uk/v2.0',
            'urlAccessToken' => 'https://identity.moneyhub.co.uk/oidc/token',
            'urlResourceOwnerDetails' => 'https://api-dev.moneyhub.co.uk/v2.0',
        ];
        $httpClientMock = $this->createMock(ClientInterface::class);
        $this->basicAuth = new BasicAuth($config, $httpClientMock);
    }

    public function testWithHttpClient(): void
    {
        $auth = $this->basicAuth->withHttpClient(new HttpClientFake());
        $this->assertWithHttpClient($auth);
    }

    public function testWithScopes(): void
    {
        $auth = $this->basicAuth->withScopes([Users::USER_READ_SCOPE]);
        $this->assertWithScopes($auth);
    }

    public function testWithEmptyScopesException(): void
    {
        $this->expectException(EmptyScopesException::class);
        $this->basicAuth->withScopes([]);
    }

    public function testWithGrant(): void
    {
        $auth = $this->basicAuth->withGrantType(AuthParams::GRANT_CLIENT_CREDENTIALS);
        $this->assertWithHGrantType($auth);
    }

    public function testToken(): void
    {
        $auth = $this->basicAuth->withHttpClient(new HttpClientFake());
        $token = $auth->getToken();
        self::assertSame('XdUtqZW5HWlJUbnJpeUxXRnZuS2tzTjNvLWFu', $token->getToken());
    }

    /**
     * @throws ReflectionException
     */
    private function assertWithHGrantType(BasicAuth $auth): void
    {
        $property = (new ReflectionObject($auth))->getProperty('grantType');
        $property->setAccessible(true);
        self::assertSame(AuthParams::GRANT_CLIENT_CREDENTIALS, $property->getValue($auth));
    }

    private function assertWithHttpClient(BasicAuth $auth): void
    {
        $property = (new ReflectionObject($auth))->getProperty('httpClient');
        $property->setAccessible(true);
        self::assertInstanceOf(HttpClientFake::class, $property->getValue($auth));
    }

    private function assertWithScopes(BasicAuth $auth): void
    {
        $property = (new ReflectionObject($auth))->getProperty('scopes');
        $property->setAccessible(true);
        self::assertSame([Users::USER_READ_SCOPE], $property->getValue($auth));
    }
}
