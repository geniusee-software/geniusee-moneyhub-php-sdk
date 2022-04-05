<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Categories;

use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthParams;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use Geniusee\MoneyHubSdk\Validator\Validator;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class Categories
{
    public const CATEGORIES_READ_SCOPE = 'categories:read';
    public const CATEGORY_WRITE_SCOPE = 'categories:write';
    private const CATEGORIES_PATH = 'https://api.moneyhub.co.uk/v2.0/categories/';
    private const CATEGORY_GROUPS_PATH = 'https://api.moneyhub.co.uk/v2.0/category-groups/';

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

    public function all(string $userId): CategoriesCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::CATEGORIES_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::CATEGORIES_PATH),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new CategoriesCollection($result);
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function one(string $userId, string $categoryId): Category
    {
        if ($this->scopes === []) {
            $this->scopes = [self::CATEGORIES_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::CATEGORIES_PATH . $categoryId),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{categoryId:string, group:string, name?:?string, key?:?string} $category
         */
        $category = JSON::decode($response->getBody()->getContents())['data'] ?? throw new CategoryNotFoundException();

        return new Category(
            $category['categoryId'],
            $category['group'],
            $category['name'] ?? null,
            $category['key'] ?? null,
        );
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function allCategoryGroups(string $userId): CategoriesGroupCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::CATEGORIES_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::CATEGORY_GROUPS_PATH),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{array{id:string, key:string}} $categoryGroup
         */
        $categoryGroup = JSON::decode($response->getBody()->getContents());

        return new CategoriesGroupCollection($categoryGroup);
    }

    public function create(string $userId): Category
    {
        $rules = new Collection(
            [
                'group' => [new NotBlank(), new Type('string')],
                'name' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::CATEGORY_WRITE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request(
                'POST',
                self::CATEGORIES_PATH,
                [],
                JSON::encode($this->body)
            ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var array{categoryId:string, group:string, name?:?string, key?:?string} $category
         */
        $category = JSON::decode($response->getBody()->getContents())['data'];

        return new Category(
            $category['categoryId'],
            $category['group'],
            $category['name'] ?? null,
            $category['key'] ?? null,
        );
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
