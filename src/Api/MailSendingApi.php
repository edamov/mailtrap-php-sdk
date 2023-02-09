<?php

declare(strict_types=1);

namespace Mailtrap\Api;

use Mailtrap\Mail;
use Psr\Http\Message\ResponseInterface;

class MailSendingApi extends HttpApi
{
    public function send(Mail $mail): ResponseInterface
    {
        return $this->httpPost('/api/send', $mail->toRequestParams());
    }
}
