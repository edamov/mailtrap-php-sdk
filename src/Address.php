<?php

declare(strict_types=1);

namespace Mailtrap;

use Webmozart\Assert\Assert;

class Address
{
    public function __construct(
        public readonly string $email,
        public readonly string $name = '',
    ) {
        Assert::email($this->email);
    }

    public function toArray()
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
