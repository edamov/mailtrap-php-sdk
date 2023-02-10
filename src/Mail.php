<?php

declare(strict_types=1);

namespace Mailtrap;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class Mail
{
    public function __construct(
        private readonly Sender     $sender,
        private readonly Recipients $recipients,
        private readonly string     $subject,
        private readonly string     $text = '',
        private readonly string     $html = ''
    ) {
        Assert::minLength($subject, 1);
        if ($text) {
            Assert::minLength($text, 1);
        }

        if ($html) {
            Assert::minLength($html, 1);
        }

        if (empty($text) && empty($html)) {
            throw new InvalidArgumentException('At least one of the "text" or "html" should be present');
        }
    }

    public function toRequestParams(): array
    {
        return [
            'from' => $this->sender->toArray(),
            'to' => $this->recipients->toArray(),
            'subject' => $this->subject,
            'text' => $this->text,
            'html' => $this->html,
        ];
    }
}
