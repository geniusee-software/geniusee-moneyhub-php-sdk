<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Accounts;

use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Exception\RequestException;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use Geniusee\MoneyHubSdk\Validator\Validator;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class Accounts
{
    public const ACCOUNTS_READ_SCOPE = 'accounts:read';
    public const ACCOUNTS_TRANSACTION_READ_ALL_SCOPE = 'transactions:read:all';
    public const ACCOUNTS_DETAILS_READ_SCOPE = 'accounts_details:read';
    public const ACCOUNTS_STANDING_ORDERS_READ_SCOPE = 'standing_orders:read';
    public const ACCOUNTS_STANDING_ORDERS_DETAIL_READ_SCOPE = 'standing_orders_detail:read';
    public const ACCOUNTS_WRITE_ALL_SCOPE_SCOPE = 'accounts:write:all';
    private const ACCOUNTS_PATH = 'https://api.moneyhub.co.uk/v2.0/accounts';
    private array $scopes = [];
    private array $params = [];
    private array $body = [];
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
        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }

    public function withBodyParams(array $body): self
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function all(string $userId): AccountsCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::ACCOUNTS_PATH),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new AccountsCollection($result);
    }

    public function one(string $userId, string $accountId): Account
    {
        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::ACCOUNTS_PATH . '/' . $accountId),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new AccountsCollection($result))->get()[0];
    }

    public function createSingleAccount(string $userId): Account
    {
        $rules = new Collection(
            [
                'accountName' => [new NotBlank(), new Type('string')],
                'providerName' => [new NotBlank(), new Type('string')],
                'type' => [new NotBlank(), new Type('string')],
                'balance' => new Collection(
                    [
                        'amount' => new Collection(
                            [
                                'value' => [new NotBlank(), new Type('integer')],
                            ],
                        ),
                        'date' => [new NotBlank(), new Date()],
                    ]
                ),
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE, self::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::ACCOUNTS_PATH, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new AccountsCollection($result))->get()[0];
    }

    public function updateSingleAccount(string $userId, string $accountId): Account
    {
        $rules = new Collection(
            [
                'accountName' => [new NotBlank(), new Type('string')],
                'providerName' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE, self::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('PATCH', self::ACCOUNTS_PATH . '/' . $accountId, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new AccountsCollection($result))->get()[0];
    }

    public function delete(string $userid, string $accountId): void
    {
        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::ACCOUNTS_PATH . '/' . $accountId),
            $this->prepareRequestOptions($userid)
        );

        if ($response->getStatusCode() !== 204) {
            throw new RequestException(
                sprintf(
                    'Account %s did not delete. Error %s',
                    $accountId,
                    $response->getBody()
                        ->getContents()
                )
            );
        }
    }

    public function retrieveTheHistoricalBalancesForAnAccount(
        string $userId,
        string $accountId
    ): AccountBalanceCollection {
        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::ACCOUNTS_PATH . '/' . $accountId . '/balances'),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new AccountBalanceCollection($result);
    }

    public function addNewBalanceForAnAccount(string $userId, string $accountId): AccountBalanceCollection
    {
        $rules = new Collection(
            [
                'amount' => new Collection(
                    [
                        'value' => [new NotBlank(), new Type('integer')],
                    ],
                ),
                'date' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::ACCOUNTS_READ_SCOPE, self::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request(
                'POST',
                self::ACCOUNTS_PATH . '/' . $accountId . '/balances',
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new AccountBalanceCollection($result);
    }

    /**
     * @psalm-return array{headers:array}
     */
    private function prepareRequestOptions(?string $userId = null): array
    {
        $grantType = $this->grantType === '' ? AuthParams::GRANT_CLIENT_CREDENTIALS : $this->grantType;

        $auth = $this->authorization
            ->withScopes($this->scopes)
            ->withGrantType($grantType)
            ->withSubject($userId);

        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $auth->getToken(),
                'content-type' => 'application/json',
            ],
            'query' => $this->params,
        ];
    }
}
