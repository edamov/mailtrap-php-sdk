<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Mail;
use Mailtrap\Recipient;
use Mailtrap\Recipients;
use Mailtrap\Sender;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class MailTest extends TestCase
{
    public function testCreatingMail()
    {
        $sender = new Sender('test-sender@gmail.com', 'Sender Name');
        $recipients = (new Recipients())->add(
            new Recipient('test-recipient@gmail.com', 'Recipient Name')
        );

        $mail = new Mail($sender, $recipients, 'Subject', 'Email body');

        $this->assertEquals([
            'from' => [
                'email' => 'test-sender@gmail.com',
                'name' => 'Sender Name',
            ],
            'to' => [
                [
                    'email' => 'test-recipient@gmail.com',
                    'name' => 'Recipient Name',
                ],
            ],
            'subject' => 'Subject',
            'text' => 'Email body',
            'html' => '',
        ], $mail->toRequestParams());
    }

    public function testCreatingMailWithInvalidSubject()
    {
        $this->expectException(InvalidArgumentException::class);

        $sender = new Sender('test-sender@gmail.com', 'Sender Name');
        $recipients = (new Recipients())->add(
            new Recipient('test-recipient@gmail.com', 'Recipient Name')
        );

        new Mail($sender, $recipients, '', 'Email body');
    }

    public function testCreatingMailWithEmptyBody()
    {
        $this->expectException(InvalidArgumentException::class);

        $sender = new Sender('test-sender@gmail.com', 'Sender Name');
        $recipients = (new Recipients())->add(
            new Recipient('test-recipient@gmail.com', 'Recipient Name')
        );

        new Mail($sender, $recipients, 'Subject');
    }
}
