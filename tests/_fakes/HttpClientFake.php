<?php

declare(strict_types=1);

namespace Geniusee\Tests\_fakes;

use Geniusee\MoneyHubSdk\Helpers\JSON;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
final class HttpClientFake implements ClientInterface
{
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return new Response(
            200,
            [],
            JSON::encode(
                [
                    'access_token' => 'XdUtqZW5HWlJUbnJpeUxXRnZuS2tzTjNvLWFu',
                    'expires_in' => 600,
                    'token_type' => 'Bearer',
                ]
            )
        );
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        return new Promise();
    }

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        return new Response();
    }

    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        return new Promise();
    }

    public function getConfig(?string $option = null): array
    {
        return [];
    }
}
