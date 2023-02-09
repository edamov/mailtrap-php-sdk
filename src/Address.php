<?php

declare(strict_types=1);

namespace Mailtrap;

class Address
{
    public function __construct(
        public readonly string $email,
        public readonly string $name = '',
    ) {
        //todo: validate email
    }

    public function toArray()
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
