<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\NotificationThresholds;

use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Exception\RequestException;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use Geniusee\MoneyHubSdk\Validator\Validator;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class NotificationThresholds
{
    private const THRESHOLDS_PATH = 'https://api.moneyhub.co.uk/v2.0/accounts/%s/notification-thresholds/';
    private const THRESHOLDS_ACCOUNTS_PATH = 'https://api.moneyhub.co.uk/v2.0/accounts/%s/notification-thresholds/';

    private array $scopes = [];
    private array $params = [];
    private string $grantType = '';
    private array $body = [];

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

    public function withBodyParams(array $body): self
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function all(string $userId, string $accountId): NotificationThresholdsCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', sprintf(self::THRESHOLDS_PATH, $accountId), ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new NotificationThresholdsCollection($result);
    }

    public function create(string $userId, string $accountId): NotificationThreshold
    {
        $rules = new Collection(
            [
                'type' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE, Accounts::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request(
                'POST',
                sprintf(
                    self::THRESHOLDS_ACCOUNTS_PATH,
                    $accountId
                ),
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{type:string, value?:?integer, id?:?string} $threshold
         */
        $threshold = JSON::decode($response->getBody()->getContents())['data'];

        return new NotificationThreshold(
            $threshold['type'],
            $threshold['value'] ?? null,
            $threshold['id'] ?? null,
        );
    }

    public function update(string $userId, string $accountId, string $thresholdId): NotificationThreshold
    {
        $rules = new Collection(
            [
                'type' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE, Accounts::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request(
                'PATCH',
                sprintf(
                    self::THRESHOLDS_ACCOUNTS_PATH,
                    $accountId
                ) . $thresholdId,
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{type:string, value?:?integer, id?:?string} $threshold
         */
        $threshold = JSON::decode($response->getBody()->getContents())['data'];

        return new NotificationThreshold(
            $threshold['type'],
            $threshold['value'] ?? null,
            $threshold['id'] ?? null,
        );
    }

    public function delete(string $userId, string $accountId, string $thresholdId): void
    {
        if ($this->scopes === []) {
            $this->scopes = [Accounts::ACCOUNTS_READ_SCOPE, Accounts::ACCOUNTS_WRITE_ALL_SCOPE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request(
                'DELETE',
                sprintf(
                    self::THRESHOLDS_ACCOUNTS_PATH,
                    $accountId
                ) . $thresholdId,
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
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
