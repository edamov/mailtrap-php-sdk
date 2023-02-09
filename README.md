# PHP SDK for Mailtrap API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/edamov/mailtrap-php-sdk.svg?style=flat-square)](https://packagist.org/packages/mailtrap/mailtrap-php-sdk)
[![Tests](https://img.shields.io/github/actions/workflow/status/edamov/mailtrap-php-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mailtrap/mailtrap-php-sdk/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/edamov/mailtrap-php-sdk.svg?style=flat-square)](https://packagist.org/packages/edamov/mailtrap-php-sdk)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require edamov/mailtrap-php-sdk
```

## Usage
### Base example
```php
use Mailtrap\Mail;
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
