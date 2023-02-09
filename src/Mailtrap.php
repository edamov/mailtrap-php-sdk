<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api\MailSendingApi;
use Mailtrap\HttpClient\HttpClientConfigurator;
use Mailtrap\HttpClient\RequestBuilder;
use Psr\Http\Client\ClientInterface;

class Mailtrap
{
    private ClientInterface $httpClient;

    private function __construct(HttpClientConfigurator $httpClientConfigurator)
    {
        $this->requestBuilder = new RequestBuilder();
        $this->httpClient = $httpClientConfigurator->createConfiguredClient();
    }

    public static function create(string $apiKey): self
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($apiKey);

        return new self($httpClientConfigurator);
    }

    public function mailSendingApi(): MailSendingApi
    {
        return new MailSendingApi($this->httpClient, $this->requestBuilder);
    }
}
