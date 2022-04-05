<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Tax;

use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use GuzzleHttp\Psr7\Request;

final class Taxes
{
    public const TAX_READ_SCOPE = 'tax:read';
    private const TAXES_URL = 'https://api.moneyhub.co.uk/v2.0/tax';
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

    public function retrieveTransactions(
        string $userId,
        string $startDate,
        string $endDate,
        ?string $projectId = null,
        ?string $accountId = null
    ): TaxesCollection {
        if ($this->scopes === []) {
            $this->scopes = [self::TAX_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TAXES_URL, ),
            $this->prepareRequestOptions(
                $userId,
                array_filter([
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'projectId' => $projectId,
                    'accountId' => $accountId,
                ])
            )
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new TaxesCollection($result);
    }

    /**
     * @psalm-return array{headers:array}
     */
    private function prepareRequestOptions(string $userId, array $params = []): array
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
            'query' => $params,
        ];
    }
}
