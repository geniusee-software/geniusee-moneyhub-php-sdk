<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\Users;

use DG\BypassFinals;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Exception\EmptyScopesException;
use Geniusee\MoneyHubSdk\Exception\RequestException;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use Geniusee\MoneyHubSdk\Users\Users;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionObject;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UsersTest extends TestCase
{
    private ApiClient $apiMock;
    private Authorization $authMock;

    protected function setUp(): void
    {
        BypassFinals::enable();
        $this->apiMock = $this->createMock(ApiClient::class);
        $this->authMock = $this->createMock(Authorization::class);
    }

    /**
     * @throws EmptyScopesException
     */
    public function testUserScopes(): void
    {
        $users = new Users($this->authMock, $this->apiMock);
        $scopes = [Users::USER_READ_SCOPE];
        $users = $users->withScopes($scopes);

        $this->assertScopes($users, $scopes);
    }

    public function testWithGrantType(): void
    {
        $users = (new Users($this->authMock, $this->apiMock))
            ->withGrantType(AuthParams::GRANT_CLIENT_CREDENTIALS);

        $this->assertWithGrantType($users);
    }

    /**
     * @throws ReflectionException
     */
    public function testWithParams(): void
    {
        $params = ['limit' => 100, 'offset' => 0];
        $users = new Users($this->authMock, $this->apiMock);
        $users = $users->withParams($params);

        $this->assertWithParams($users, $params);
    }

    /**
     * @throws ReflectionException
     */
    public function testParamsLimitMaxException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $params = ['limit' => 1001, 'offset' => 0];
        $users = new Users($this->authMock, $this->apiMock);
        $users = $users->withParams($params);

        $this->assertWithParams($users, $params);
    }

    /**
     * @throws ReflectionException
     */
    public function testParamsLimitMinException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $params = ['limit' => -1, 'offset' => 0];
        $users = new Users($this->authMock, $this->apiMock);
        $users = $users->withParams($params);

        $this->assertWithParams($users, $params);
    }

    /**
     * @throws ReflectionException
     */
    public function testParamsOffsetMinException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $params = ['limit' => 1, 'offset' => -2];
        $users = new Users($this->authMock, $this->apiMock);
        $users = $users->withParams($params);

        $this->assertWithParams($users, $params);
    }

    /**
     * @throws ReflectionException
     */
    public function testParamsOffsetMaxException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $params = ['limit' => 1, 'offset' => 1000000001];
        $users = new Users($this->authMock, $this->apiMock);
        $users = $users->withParams($params);

        $this->assertWithParams($users, $params);
    }

    public function testDeleteException(): void
    {
        $this->expectException(RequestException::class);
        $httpResponse = new Response(500);
        $this->apiMock->method('send')->willReturn($httpResponse);
        $users = new Users($this->authMock, $this->apiMock);
        $users->delete('some_id');
    }

    public function testDelete(): void
    {
        $httpResponse = new Response(204);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $users = new Users($this->authMock, $this->apiMock);
        $users->delete('some_id');
    }

    public function testAll(): void
    {
        $body = file_get_contents('./tests/_fixture/money_hub_answer_users_all.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $users = new Users($this->authMock, $this->apiMock);
        $users->all();
    }

    public function testOne(): void
    {
        $body = file_get_contents('./tests/_fixture/money_hub_user_one.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $users = new Users($this->authMock, $this->apiMock);
        $users->one('some_id');
    }

    public function testCreate(): void
    {
        $body = file_get_contents('./tests/_fixture/money_hub_user_one.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $users = new Users($this->authMock, $this->apiMock);
        $users->create();
    }

    /**
     * @throws ReflectionException
     */
    private function assertWithGrantType(Users $users): void
    {
        $property = (new ReflectionObject($users))->getProperty('grantType');
        $property->setAccessible(true);

        self::assertSame(AuthParams::GRANT_CLIENT_CREDENTIALS, $property->getValue($users));
    }

    private function assertScopes(Users $users, array $expected): void
    {
        $property = (new ReflectionObject($users))->getProperty('scopes');
        $property->setAccessible(true);

        self::assertSame($expected, $property->getValue($users));
    }

    /**
     * @throws ReflectionException
     */
    private function assertWithParams(Users $users, array $expected): void
    {
        $property = (new ReflectionObject($users))->getProperty('params');
        $property->setAccessible(true);

        self::assertSame($expected, $property->getValue($users));
    }
}
