<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Counterparties;

use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use GuzzleHttp\Psr7\Request;

final class Counterparties
{
    private const GLOBAL_COUNTERPARTIES_PATH = 'https://api.moneyhub.co.uk/v2.0/global-counterparties';
    private const ACCOUNT_COUNTERPARTIES_PATH = 'https://api.moneyhub.co.uk/v2.0/accounts/';

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

    public function globalCounterparties(): GlobalCounterpartiesCollection
    {
        $response = $this->apiClient->send(
            new Request('GET', self::GLOBAL_COUNTERPARTIES_PATH),
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new GlobalCounterpartiesCollection($result);
    }

    public function accountCounterparties(string $userId, string $accountId): GlobalCounterpartiesCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE, 'transactions:read:all'];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::ACCOUNT_COUNTERPARTIES_PATH . $accountId . '/counterparties'),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new GlobalCounterpartiesCollection($result);
    }

    /**
     * @psalm-return array{headers:array}
     */
    private function prepareRequestOptions(string $userId): array
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
