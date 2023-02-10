<?php

declare(strict_types=1);

namespace Mailtrap\Tests;

use Mailtrap\Address;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AddressTest extends TestCase
{
    public function testCreatingAddress()
    {
        $address = new Address('example@email.com', 'Name Surname');

        $this->assertEquals('example@email.com', $address->email);
        $this->assertEquals('Name Surname', $address->name);
        $this->assertEquals([
            'email' => 'example@email.com',
            'name' => 'Name Surname',
        ], $address->toArray());
    }

    public function testCreatingAddressWithInvalidEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        new Address('example@', 'Name Surname');
    }
}
