<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Http;

use Geniusee\MoneyHubSdk\Exception\RequestException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class ApiClient
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        try {
            return $this->httpClient->send($request, $options);
        } catch (Throwable $e) {
            throw new RequestException($e->getMessage());
        }
    }
}
