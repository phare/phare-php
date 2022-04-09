# PHP integration for the Phare user testing platform

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phare/phare-php.svg?style=flat-square)](https://packagist.org/packages/phare/phare-php)
[![Tests](https://github.com/phare/phare-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/phare/phare-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/phare/phare-php.svg?style=flat-square)](https://packagist.org/packages/phare/phare-php)

Integrate the [Phare privacy first, in-app user testing platform](https://phare.app/) into your PHP applications.

### Using Laravel?

Check out the [Phare Laravel](https://github.com/phare/phare-laravel) package.

## Installation

You can install the package via composer:

```bash
composer require phare/phare-php
```

## Usage

You need to things to make Phare work on your application:
- a unique user Token that need to be generated in your backend, to maintain user privacy.
- load the Phare script in your HTML

This package aim to make that process easier by providing you with a method capable of doing both things at once:

```php
$script = \Phare\PharePHP\Script::render(
    'your-public-key',
    'your-private-key',
    'a-random-string-of-your-choice',
    'logged-user-id'
);   
```

A real-life example that create a functioning Phare's script in your page HTML would look like this:

```html
<html>
    <head>
      ...
      
      <?php
      \Phare\PharePHP\Script::make(
          '800d13f1-9c25-4165-97c1-28a077bd5aca',
          'kU3UKLgrxp8qb1uIKhnN5yD9xHjNCaxptr6IVCOD',
          'WeLikePrivacyFirstUserTesting',
          $user->id
      );   
      ?>
    </head>
    <body>
      ...
    </body>
</html>
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

- [Phare](https://github.com/phare)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
