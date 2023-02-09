<?php

declare(strict_types=1);

namespace Mailtrap;
class Mail
{
    public function __construct(
        private readonly Sender     $sender,
        private readonly Recipients $recipients,
        private readonly string     $subject,
        private readonly string     $text = '',
        private readonly string     $html = ''
    ) {
        //todo: validate that at least text or html is not empty
        //todo: validate that subject is not empty
    }

    public static function fromArray(array ...$params): self
    {
        //todo: validate all required parameters: from, to, subject, one of text or html
        //todo: create instance of Mail class
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
