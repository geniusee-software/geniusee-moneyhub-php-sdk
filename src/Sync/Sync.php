<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Sync;

use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use GuzzleHttp\Psr7\Request;

final class Sync
{
    private const SYNC_PATH = 'https://api.moneyhub.co.uk/v2.0/sync/';
    private array $scopes = [];

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
     * @throws SyncNotFoundException
     */
    public function syncAnExistingConnection(string $userId, string $connectionId): SyncDto
    {
        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE, Accounts::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::SYNC_PATH . $connectionId),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{status:string} $sync
         */
        $sync = JSON::decode($response->getBody()->getContents())['data'] ?? throw new SyncNotFoundException();

        return new SyncDto($sync['status']);
    }

    /**
     * @psalm-return array{headers:array}
     */
    private function prepareRequestOptions(string $userId): array
    {
        $auth = $this->authorization
            ->withScopes($this->scopes)
            ->withGrantType(AuthParams::GRANT_CLIENT_CREDENTIALS)
            ->withSubject($userId);

        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $auth->getToken(),
                'content-type' => 'application/json',
            ],
        ];
    }
}
