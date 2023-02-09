<?php

declare(strict_types=1);

namespace Mailtrap\Tests\Api;

use Mailtrap\Api\MailSendingApi;
use Mailtrap\Mail;
use Mailtrap\Recipient;
use Mailtrap\Recipients;
use Mailtrap\Sender;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class MailSendingApiTest extends TestCase
{
    protected function getApiClass()
    {
        return MailSendingApi::class;
    }

    protected function getApiInstance($apiKey = null): MailSendingApi
    {
        return parent::getApiInstance($apiKey);
    }

    public function testSending()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/api/send');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json']));

        $api = $this->getApiInstance();

        $sender = new Sender('test-sender@gmail.com', 'Sender Name');
        $recipients = (new Recipients())->add(
            new Recipient('test-recipient@gmail.com', 'Recipient Name')
        );

        $mail = new Mail($sender, $recipients, 'Subject', 'Email body');

        $response = $api->send($mail);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
