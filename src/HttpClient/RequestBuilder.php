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
    /**
     * @var RequestFactoryInterface|null
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    public function create(string $method, string $uri, array $headers = [], $body = null): RequestInterface
    {
        $stringBody = is_array($body) ? json_encode($body, JSON_THROW_ON_ERROR) : $body;
        $stream = $this->getStreamFactory()->createStream($stringBody);

        return $this->createRequest($method, $uri, $headers, $stream);
    }

    /**
     * @return RequestFactoryInterface
     */
    private function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    /**
     * @param  RequestFactoryInterface $requestFactory
     * @return $this
     */
    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * @return StreamFactoryInterface
     */
    private function getStreamFactory(): StreamFactoryInterface
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }

    /**
     * @param  StreamFactoryInterface $streamFactory
     * @return $this
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    /**
     * @param  string           $method
     * @param  string           $uri
     * @param  array            $headers
     * @param  StreamInterface  $stream
     * @return RequestInterface
     */
    private function createRequest(string $method, string $uri, array $headers, StreamInterface $stream): RequestInterface
    {
        $request = $this->getRequestFactory()->createRequest($method, $uri);
        $request = $request->withBody($stream);
        foreach ($headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        return $request;
    }

    /**
     * @param  array        $item
     * @param  string       $key
     * @return mixed|string
     */
    private function getItemValue(array $item, string $key)
    {
        if (is_bool($item[$key])) {
            return (string) $item[$key];
        }

        return $item[$key];
    }
}
