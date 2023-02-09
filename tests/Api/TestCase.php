<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api;

use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private $requestMethod;

    private $requestUri;

    private $requestHeaders = [];

    private $requestBody;

    private $httpResponse;

    protected function setUp(): void
    {
        $this->reset();
    }

    abstract protected function getApiClass();

    /**
     * This will give you a mocked API. Optionally you can provide mocked dependencies.
     */
    protected function getApiMock($httpClient = null, $requestClient = null)
    {
        if (null === $httpClient) {
            $httpClient = $this->getMockBuilder(ClientInterface::class)
                ->setMethods(['sendRequest'])
                ->getMock();
            $httpClient
                ->expects($this->any())
                ->method('sendRequest');
        }
        if (null === $requestClient) {
            $requestClient = $this->getMockBuilder('Mailtrap\HttpClient\RequestBuilder')
                ->setMethods(['create'])
                ->getMock();
        }

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['httpGet', 'httpPost', 'httpPostRaw', 'httpDelete', 'httpPut'])
            ->setConstructorArgs([$httpClient, $requestClient])
            ->getMock();
    }

    /**
     * This will return you a real API instance with mocked dependencies.
     */
    protected function getApiInstance($apiKey = null)
    {
        $httpClient = $this->getMockBuilder(ClientInterface::class)
            ->setMethods(['sendRequest'])
            ->getMock();
        $httpClient
            ->method('sendRequest')
            ->willReturn(null === $this->httpResponse ? new Response() : $this->httpResponse);

        $requestClient = $this->getMockBuilder('Mailtrap\HttpClient\RequestBuilder')
            ->setMethods(['create'])
            ->getMock();

        if (null !== $this->requestMethod
            || null !== $this->requestUri
            || ! empty($this->requestHeaders)
            || ! empty($this->requestBody)
        ) {
            $requestClient
                ->expects($this->once())
                ->method('create')
                ->with(
                    $this->callback([$this, 'validateRequestMethod']),
                    $this->callback([$this, 'validateRequestUri']),
                    $this->callback([$this, 'validateRequestHeaders']),
                    $this->callback([$this, 'validateRequestBody'])
                )
                ->willReturn(new Request('GET', '/'));
        }

        $class = $this->getApiClass();

        if (null !== $apiKey) {
            return new $class($httpClient, $requestClient, $apiKey);
        }

        return new $class($httpClient, $requestClient);
    }

    public function validateRequestMethod($method)
    {
        return $this->verifyProperty($this->requestMethod, $method);
    }

    public function validateRequestUri($uri)
    {
        return $this->verifyProperty($this->requestUri, $uri);
    }

    public function validateRequestHeaders($headers)
    {
        return $this->verifyProperty($this->requestHeaders, $headers);
    }

    public function validateRequestBody($body)
    {
        if ($this->verifyProperty($this->requestBody, $body)) {
            return true;
        }

        // Assert: $body is prepared for a "multipart stream".

        // Check length
        if (count($this->requestBody) !== count($body)) {
            return false;
        }

        // Check every item in body.
        foreach ($body as $item) {
            if ('resource' === $this->requestBody[$item['name']] && is_resource($item['content'])) {
                continue;
            }
            if ($this->requestBody[$item['name']] !== $item['content']) {
                return false;
            }
        }

        return true;
    }

    protected function reset()
    {
        $this->httpResponse = null;
        $this->requestMethod = null;
        $this->requestUri = null;
        $this->requestHeaders = null;
        $this->requestBody = null;
    }

    /**
     * Set a response that you want to client to respond with.
     */
    public function setHttpResponse(ResponseInterface $httpResponse)
    {
        $this->httpResponse = $httpResponse;
    }

    /**
     * Set request http method.
     */
    public function setRequestMethod(string $httpMethod)
    {
        $this->requestMethod = $httpMethod;
    }

    public function setRequestUri(string $requestUri)
    {
        $this->requestUri = $requestUri;
    }

    public function setRequestHeaders(array $requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
    }

    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    private function verifyProperty($property, $value)
    {
        if (null === $property) {
            return true;
        }

        return is_callable($property) ? call_user_func($property, $value) : $value === $property;
    }
}
