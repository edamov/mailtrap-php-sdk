<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Mailtrap\Exceptions\HttpClientException;
use Mailtrap\Exceptions\HttpServerException;
use Mailtrap\Exceptions\UnknownErrorException;
use Mailtrap\HttpClient\RequestBuilder;
use Psr\Http\Client as Psr18;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class HttpApi
{
    /**
     * The HTTP client.
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var RequestBuilder
     */
    protected $requestBuilder;

    public function __construct($httpClient, RequestBuilder $requestBuilder)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
    }

    protected function handleErrors(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 400:
                throw HttpClientException::badRequest($response);
            case 401:
                throw HttpClientException::unauthorized($response);
            case 402:
                throw HttpClientException::requestFailed($response);
            case 403:
                throw HttpClientException::forbidden($response);
            case 404:
                throw HttpClientException::notFound($response);
            case 409:
                throw HttpClientException::conflict($response);
            case 413:
                throw HttpClientException::payloadTooLarge($response);
            case 429:
                throw HttpClientException::tooManyRequests($response);
            case 500 <= $statusCode:
                throw HttpServerException::serverError($statusCode);
            default:
                throw new UnknownErrorException();
        }
    }

    protected function httpGet(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('GET', $path, $requestHeaders)
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            $this->handleErrors($response);
        }

        return $response;
    }

    protected function httpPost(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRaw($path, $this->createRequestBody($parameters), $requestHeaders);
    }

    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            $this->handleErrors($response);
        }

        return $response;
    }

    protected function httpPut(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('PUT', $path, $requestHeaders, $this->createRequestBody($parameters))
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            $this->handleErrors($response);
        }

        return $response;
    }

    protected function httpDelete(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createRequestBody($parameters))
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            $this->handleErrors($response);
        }

        return $response;
    }

    private function createRequestBody(array $parameters): array
    {
        $resources = [];
        foreach ($parameters as $key => $values) {
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                $resources[] = [
                    'name' => $key,
                    'content' => $value,
                ];
            }
        }

        return $resources;
    }
}
