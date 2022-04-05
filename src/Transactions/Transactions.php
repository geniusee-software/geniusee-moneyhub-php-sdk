<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

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

final class Transactions
{
    public const TRANSACTIONS_READ_ALL_SCOPE = 'transactions:read:all';
    public const TRANSACTIONS_WRITE_ALL = 'transactions:write:all';
    public const TRANSACTIONS_READ_IN_SCOPE = 'transactions:read:in';
    public const TRANSACTIONS_READ_OUT_SCOPE = 'transactions:read:out';
    private const TRANSACTIONS_PATH = 'https://api.moneyhub.co.uk/v2.0/transactions/';
    private const TRANSACTIONS_COLLECTION_PATH = 'https://api.moneyhub.co.uk/v2.0/transactions-collection';

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

    public function all(string $userId): TransactionCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_OUT_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TRANSACTIONS_PATH, ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new TransactionCollection($result);
    }

    public function one(string $userId, string $transactionId): Transaction
    {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TRANSACTIONS_PATH . $transactionId, ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new TransactionCollection($result))->get()[0];
    }

    public function createSingleTransaction(string $userId): Transaction
    {
        (new Validator())->validate($this->body, [TransactionCreateRules::rules()]);

        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE, self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::TRANSACTIONS_PATH, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new TransactionCollection($result))->get()[0];
    }

    /**
     * @psalm-return array<array-key, TransactionId>
     */
    public function createMultipleTransactions(string $userId): array
    {
        $validator = new Validator();

        /**
         * @psalm-var array $bodyItem
         */
        foreach ($this->body as $bodyItem) {
            $validator->validate($bodyItem, [TransactionCreateRules::rules()]);
        }

        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE, self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::TRANSACTIONS_COLLECTION_PATH, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return array_map(
            /**
             * @psalm-param  array{id:string} $item
             */
            static fn (array $item) => new TransactionId($item['id']),
            $result['data']
        );
    }

    public function updateSingleTransaction(string $userId, string $transactionId): Transaction
    {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE, self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('PATCH', self::TRANSACTIONS_PATH . $transactionId, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new TransactionCollection($result))->get()[0];
    }

    public function transactionAttachments(string $userId, string $transactionId): TransactionAttachmentCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TRANSACTIONS_PATH . $transactionId . '/files', ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new TransactionAttachmentCollection($result);
    }

    public function retrieveTransactionAttachments(
        string $userId,
        string $transactionId,
        string $fileId
    ): TransactionAttachment {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TRANSACTIONS_PATH . $transactionId . '/files/' . $fileId, ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new TransactionAttachmentCollection($result))->get()[0];
    }

    /**
     * @psalm-return array<array-key, TransactionSplit>
     */
    public function retrieveTransactionSplit(
        string $userId,
        string $transactionId,
    ): array {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::TRANSACTIONS_PATH . $transactionId . '/splits/', ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return array_map(
            /**
             * @psalm-param array{amount:int, categoryId:string, description:string, id:string, projectId:string} $item
             */
            static fn (array $item) => new TransactionSplit(
                $item['amount'],
                $item['categoryId'],
                $item['description'],
                $item['id'],
                $item['projectId'] ?? null
            ),
            $result['data']
        );
    }

    public function splitTransaction(
        string $userId,
        string $transactionId,
    ): TransactionSplit {
        $rules = new Collection(
            [
                'amount' => [new NotBlank(), new Type('integer')],
                'categoryId' => [new NotBlank(), new Type('string')],
                'description' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE, self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::TRANSACTIONS_PATH . $transactionId . '/splits', [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{amount:int, categoryId:string, description:string, id:string, projectId:string} $result
         */
        $result = JSON::decode($response->getBody()->getContents())['data'];

        return new TransactionSplit(
            $result['amount'],
            $result['categoryId'],
            $result['description'],
            $result['id'],
            $result['projectId'] ?? null
        );
    }

    /**
     * @psalm-return array<array-key, TransactionSplit>
     */
    public function pathSplitTransaction(
        string $userId,
        string $transactionId,
        string $splitId,
    ): array {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request(
                'PATCH',
                self::TRANSACTIONS_PATH . $transactionId . '/splits/' . $splitId,
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return array_map(
            /**
             * @psalm-param array{amount:int, categoryId:string, description:string, id:string, projectId:string} $item
             */
            static fn (array $item) => new TransactionSplit(
                $item['amount'],
                $item['categoryId'],
                $item['description'],
                $item['id'],
                $item['projectId'] ?? null
            ),
            $result['data']
        );
    }

    public function mergeSplitTransaction(
        string $userId,
        string $transactionId,
    ): void {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::TRANSACTIONS_PATH . $transactionId . '/splits', [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        if ($response->getStatusCode() !== 204) {
            throw new RequestException(
                sprintf(
                    'Transaction split %s  error - %s',
                    $transactionId,
                    $response->getBody()
                        ->getContents()
                )
            );
        }
    }

    public function deleteTransactionAttachments(
        string $userId,
        string $transactionId,
        string $fileId
    ): void {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_READ_ALL_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::TRANSACTIONS_PATH . $transactionId . '/files/' . $fileId, ),
            $this->prepareRequestOptions($userId)
        );

        if ($response->getStatusCode() !== 204) {
            throw new RequestException(
                sprintf(
                    'Transaction %s attachment not delete. Error %s',
                    $transactionId,
                    $response->getBody()
                        ->getContents()
                )
            );
        }
    }

    public function delete(string $userid, string $transactionId): void
    {
        if ($this->scopes === []) {
            $this->scopes = [self::TRANSACTIONS_WRITE_ALL];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::TRANSACTIONS_PATH . $transactionId, ),
            $this->prepareRequestOptions($userid)
        );

        if ($response->getStatusCode() !== 204) {
            throw new RequestException(
                sprintf(
                    'Transaction %s did not delete. Error %s',
                    $transactionId,
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
