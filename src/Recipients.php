<?php

declare(strict_types=1);

namespace Mailtrap;
class Recipients
{
    private array $collection;

    public function add(Recipient $recipient): void
    {
        $this->collection[] = $recipient;
    }

    public function toArray(): array
    {
        return array_map(function (Recipient $recipient) {
            return $recipient->toArray();
        }, $this->collection);
    }
}
