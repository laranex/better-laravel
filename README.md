# Better Laravel

- Website: https://

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laranex/better-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laranex/better-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/laranex/better-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/laranex/better-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)

A package which allows you to build a better Laravel Application

Modular Design, Job Driven Architecture and Clean Code for Laravel Application


## Installation

You can install the package via composer:

```bash
composer require laranex/better-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="better-laravel-config"
```

This is the contents of the published config file:

```php
return [

    /**
     * Register routes under routes/web and routes/api by the service provider or not.
     */
    'enable_routes' => env('BETTER_LARAVEL_ENABLE_ROUTES', true),
];
```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nay Thu Khant](https://github.com/naythukhant)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
