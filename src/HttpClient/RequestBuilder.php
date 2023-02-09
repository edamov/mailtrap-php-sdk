<?php

declare(strict_types=1);

namespace Mailtrap\HttpClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class RequestBuilder
{
    private ?RequestFactoryInterface $requestFactory;

    private ?StreamFactoryInterface $streamFactory;

    public function create(string $method, string $uri, array $headers = [], $body = null): RequestInterface
    {
        $stringBody = is_array($body) ? json_encode($body, JSON_THROW_ON_ERROR) : $body;
        $stream = $this->getStreamFactory()->createStream($stringBody);

        return $this->createRequest($method, $uri, $headers, $stream);
    }

    private function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    private function getStreamFactory(): StreamFactoryInterface
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    private function createRequest(string $method, string $uri, array $headers, StreamInterface $stream): RequestInterface
    {
        $request = $this->getRequestFactory()->createRequest($method, $uri);
        $request = $request->withBody($stream);
        foreach ($headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        return $request;
    }
}
