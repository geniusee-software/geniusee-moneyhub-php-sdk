<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Authorization;

use Geniusee\MoneyHubSdk\Exception\EmptyScopesException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Webmozart\Assert\Assert;

final class BasicAuth implements Authorization
{
    /**
     * @psalm-param array<int, string> $scopes
     */
    private array $scopes = [];

    private string $grantType = '';

    private ?string $subject = null;

    public function __construct(private array $config, private ClientInterface $httpClient)
    {
        Assert::keyExists($this->config, 'clientId');
        Assert::keyExists($this->config, 'clientSecret');
    }

    /**
     * @return $this
     * @throws EmptyScopesException
     */
    public function withScopes(array $scopes): self
    {
        if ($scopes === []) {
            throw new EmptyScopesException('Scopes should not be empty');
        }

        $clone = clone $this;
        $clone->scopes = $scopes;

        return $clone;
    }

    /**
     * @psalm-param string authorization_code|client_credentials|refresh_token $grantType
     */
    public function withGrantType(string $grantType): self
    {
        $clone = clone $this;
        $clone->grantType = $grantType;

        return $clone;
    }

    public function withHttpClient(ClientInterface $httpClient): self
    {
        $clone = clone $this;
        $clone->httpClient = $httpClient;

        return $clone;
    }

    public function withSubject(?string $subject = null): self
    {
        $clone = clone $this;
        $clone->subject = $subject;

        return $clone;
    }

    /**
     * @throws IdentityProviderException
     */
    public function getToken(): AccessToken
    {
        $provider = new GenericProvider($this->config);
        $provider->setHttpClient($this->httpClient);
        $headers = [
            'content-type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . $this->getHttpBasicCredentials(),
        ];

        $scopesToString = array_map(static fn ($value) => (string)$value, $this->scopes);

        $body = sprintf('scope=%s&grant_type=%s', implode(' ', $scopesToString), $this->grantType);

        if ($this->subject !== null) {
            $body .= sprintf('&sub=%s', $this->subject);
        }

        $request = new Request(
            'POST',
            $provider->getBaseAccessTokenUrl([]),
            $headers,
            $body
        );

        $response = $provider->getParsedResponse($request);
        Assert::isArray($response);

        return new AccessToken($response);
    }

    private function getHttpBasicCredentials(): string
    {
        return base64_encode(
            sprintf(
                '%s:%s',
                (string)$this->config['clientId'],
                (string)$this->config['clientSecret']
            )
        );
    }
}
