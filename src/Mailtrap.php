<?php

declare(strict_types=1);

namespace Mailtrap;

use Mailtrap\Api\MailSendingApi;
use Mailtrap\HttpClient\HttpClientConfigurator;
use Mailtrap\HttpClient\RequestBuilder;
use Psr\Http\Client\ClientInterface;

class Mailtrap
{
    private MailSendingApi $mailSendingApi;

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
        if (!$this->mailSendingApi) {
            $this->mailSendingApi = new MailSendingApi($this->httpClient, $this->requestBuilder);
        }

        return $this->mailSendingApi;
    }
}

//
//
//Issues:
//1) https://help.mailtrap.io/article/103-api-tokens
//- You can also give Account Amin access to the token and get access to all Projects, Inboxes, and domains on that account.
//- them in the API tokens page
//- in case of SMTP integration
//2) Не зрозуміло з документації, чи обовязково відправляти name для відправника або отримувача
//3) debug mode
//4) examples in ReADME
//5) tests
