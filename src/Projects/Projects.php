<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Projects;

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

final class Projects
{
    public const PROJECT_READ_SCOPE = 'projects:read';
    public const PROJECT_WRITE_SCOPE = 'projects:write';
    public const PROJECT_DELETE_SCOPE = 'projects:delete';
    private const PROJECT_PATH = 'https://api.moneyhub.co.uk/v2.0/projects';

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

    public function all(string $userId): ProjectsCollection
    {
        if ($this->scopes === []) {
            $this->scopes = [self::PROJECT_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::PROJECT_PATH, ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return new ProjectsCollection($result);
    }

    public function one(string $userId, string $projectId): Project
    {
        if ($this->scopes === []) {
            $this->scopes = [self::PROJECT_READ_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('GET', self::PROJECT_PATH . '/' . $projectId, ),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new ProjectsCollection($result))->get()[0];
    }

    public function createSingleProject(string $userId): Project
    {
        $rules = new Collection(
            [
                'name' => [new NotBlank(), new Type('string')],
                'type' => [new NotBlank(), new Type('string')],
            ]
        );

        (new Validator())->validate($this->body, [$rules]);

        if ($this->scopes === []) {
            $this->scopes = [self::PROJECT_WRITE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('POST', self::PROJECT_PATH, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new ProjectsCollection($result))->get()[0];
    }

    public function updateSingleProject(string $userId, string $projectId): Project
    {
        if ($this->scopes === []) {
            $this->scopes = [self::PROJECT_WRITE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('PATCH', self::PROJECT_PATH . '/' . $projectId, [], JSON::encode($this->body)),
            $this->prepareRequestOptions($userId)
        );

        /**
         * @psalm-var array{data:array<string,mixed>, meta:array{limit:int, offset:int, total:int}} $result
         */
        $result = JSON::decode($response->getBody()->getContents());

        return (new ProjectsCollection($result))->get()[0];
    }

    public function delete(string $userid, string $accountId): void
    {
        if ($this->scopes === []) {
            $this->scopes = [self::PROJECT_DELETE_SCOPE];
        }

        $response = $this->apiClient->send(
            new Request('DELETE', self::PROJECT_PATH . '/' . $accountId, ),
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
