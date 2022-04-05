<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Users;

use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Exception\RequestException;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use GuzzleHttp\Psr7\Request;

final class Users
{
    public const USER_READ_SCOPE = 'user:read';
    public const USER_WRITE_SCOPE = 'user:write';
    public const USER_CREATE_SCOPE = 'user:create';
    public const USER_DELETE_SCOPE = 'user:delete';

    private const USERS_PATH = 'https://identity.moneyhub.co.uk/users';

    private array $scopes = [];
    private array $params = [];
    private string $grantType = '';

    public function __construct(private Authorization $authorization, private ApiClient $apiClient)
    {
    }

    /**
     * @return $this
     */
    public function withScopes(array $scopes): self
    {
        $clone = clone $this;
        $clone->scopes = $scopes;

        return $clone;
    }

    /**
     * @psalm-param string client_credentials $grantType
     */
    public function withGrantType(string $grantType): self
    {
        $clone = clone $this;
        $clone->grantType = $grantType;

        return $clone;
    }

    public function withParams(array $params): self
    {
        UsersValidateParams::validate($params);

        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }

    public function all(): UsersCollection
    {
        $response = $this->apiClient->send(
            new Request('GET', self::USERS_PATH, ),
            $this->prepareRequestOptions()
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new UsersCollection($result);
    }

    public function one(string $moneyHubUserId): User
    {
        $response = $this->apiClient->send(
            new Request('GET', self::USERS_PATH . '/' . $moneyHubUserId, ),
            $this->prepareRequestOptions()
        );

        /**
         * @psalm-var array<string, mixed> $result
         */
        $result = [JSON::decode($response->getBody()->getContents())];

        return (new UsersCollection(['data' => $result]))->get()[0];
    }

    public function create(string $clientUserId = ''): User
    {
        if (!\in_array(self::USER_CREATE_SCOPE, $this->scopes, true)) {
            $this->scopes = [self::USER_CREATE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::USERS_PATH, [], JSON::encode(['clientUserId' => $clientUserId])),
            $this->prepareRequestOptions()
        );

        /**
         * @psalm-var array<string, mixed> $result
         */
        $result = [JSON::decode($response->getBody()->getContents())];

        return (new UsersCollection(['data' => $result]))->get()[0];
    }

    public function delete(string $moneyHubUserId): void
    {
        if (!\in_array(self::USER_WRITE_SCOPE, $this->scopes, true)) {
            $this->scopes = [self::USER_DELETE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::USERS_PATH . '/' . $moneyHubUserId, ),
            $this->prepareRequestOptions()
        );

        if ($response->getStatusCode() !== 204) {
            throw new RequestException(
                sprintf(
                    'User %s did not delete. Error %s',
                    $moneyHubUserId,
                    $response->getBody()
                        ->getContents()
                )
            );
        }
    }

    /**
     * @psalm-return array{headers:array}
     */
    private function prepareRequestOptions(): array
    {
        $scopes = $this->scopes === []
            ? [self::USER_READ_SCOPE]
            : $this->scopes;

        $grantType = $this->grantType === '' ? AuthParams::GRANT_CLIENT_CREDENTIALS : $this->grantType;

        $auth = $this->authorization
            ->withScopes($scopes)
            ->withGrantType($grantType);

        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $auth->getToken(),
                'content-type' => 'application/json',
            ],
            'query' => $this->params,
        ];
    }
}
