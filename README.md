# Mailtrap PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/edamov/mailtrap-php-sdk.svg?style=flat-square)](https://packagist.org/packages/edamov/mailtrap-php-sdk)
[![Tests](https://img.shields.io/github/actions/workflow/status/edamov/mailtrap-php-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/edamov/mailtrap-php-sdk/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/edamov/mailtrap-php-sdk.svg?style=flat-square)](https://packagist.org/packages/edamov/mailtrap-php-sdk)

Mailtrap PHP SDK is the Mailtrap API client for PHP developers.

To learn more about Mailtrap, refer to the [Mailtrap API Documentation](https://api-docs.mailtrap.io/).

## Installation

To get started, simply require the project using [Composer](https://getcomposer.org/).<br>
You will also need to install packages that "provide" [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation) and [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation).<br>
A list with compatible HTTP clients and client adapters can be found at [php-http.org](http://docs.php-http.org/en/latest/clients.html).

```bash
composer require edamov/mailtrap-php-sdk kriswallsmith/buzz nyholm/psr7
```

## Usage
### Base example
```php
require 'vendor/autoload.php';
use Mailtrap\Mail;
use Mailtrap\Mailtrap;
use Mailtrap\Recipient;
use Mailtrap\Recipients;
use Mailtrap\Sender;

$mailtrap = Mailtrap::create('api-key');

$sender = new Sender('sender@example.com', 'Sender Name');
$recipients = (new Recipients())->add(
    new Recipient('recipient@example.com', 'Recipient Name')
);

$mail = new Mail($sender, $recipients, 'Subject', 'Email body');

$mailtrap->mailSendingApi->send($mail);
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Artur Edamov](https://github.com/edamov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
