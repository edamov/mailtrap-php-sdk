<?php

declare(strict_types=1);

namespace Mailtrap\HttpClient;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;
final class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint = 'https://send.api.mailtrap.io';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function createConfiguredClient(): PluginClient
    {
        $plugins = [
            new Plugin\AddHostPlugin($this->getUriFactory()->createUri($this->endpoint)),
            new Plugin\HeaderDefaultsPlugin([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json'
            ]),
        ];

        return new PluginClient($this->getHttpClient(), $plugins);
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    private function getUriFactory(): UriFactoryInterface
    {
        if (null === $this->uriFactory) {
            $this->uriFactory = Psr17FactoryDiscovery::findUriFactory();
        }

        return $this->uriFactory;
    }

    public function setUriFactory(UriFactoryInterface $uriFactory): self
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    private function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }
}
