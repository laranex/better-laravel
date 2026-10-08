# Better Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)
[![Tests](https://img.shields.io/github/actions/workflow/status/laranex/better-laravel/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/laranex/better-laravel/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)
[![License](https://img.shields.io/packagist/l/laranex/better-laravel.svg?style=flat-square)](LICENSE.md)

Better Laravel is a small set of base classes and artisan generators for building Laravel applications as **modules** (controllers and features) and **domains** (jobs and requests), with **operations** grouping reusable jobs. Controllers serve features, features run jobs and operations, and route files under `routes/web` and `routes/api` are loaded automatically. It is for teams who want a modular, job-driven structure without leaving the way Laravel works.

## Documentation

Full documentation lives at **[laranex.vercel.app/better-laravel](https://laranex.vercel.app/better-laravel)**.

## Requirements

- PHP 8.1 or higher
- Laravel 10, 11, 12 or 13

## Installation

```bash
composer require laranex/better-laravel
```

Optionally publish the configuration file, the generator stubs or the welcome view:

```bash
php artisan vendor:publish --tag="better-laravel-config"
php artisan vendor:publish --tag="better-laravel-stubs"
php artisan vendor:publish --tag="better-laravel-views"
```

## Usage

Generate the pieces of a module and a domain, then wire them together:

```bash
php artisan better:route blog v1 --api      # routes/api/v1/blogs.php, loaded automatically
php artisan better:controller Blog Blog     # app/Modules/BlogModule/Http/Controllers/BlogController.php
php artisan better:feature StoreBlog Blog   # app/Modules/BlogModule/Features/StoreBlogFeature.php
php artisan better:request StoreBlog Blog   # app/Domains/Blog/Requests/StoreBlogRequest.php
php artisan better:job StoreBlog Blog       # app/Domains/Blog/Jobs/StoreBlogJob.php
php artisan better:job NotifyViaEmail Blog --queue
```

```php
use App\Domains\Blog\Jobs\NotifyViaEmailJob;
use App\Domains\Blog\Jobs\StoreBlogJob;
use App\Domains\Blog\Requests\StoreBlogRequest;
use Laranex\BetterLaravel\Cores\Controller;
use Laranex\BetterLaravel\Cores\Feature;

class BlogController extends Controller
{
    public function store(): mixed
    {
        return $this->serve(new StoreBlogFeature);
    }
}

class StoreBlogFeature extends Feature
{
    public function handle(StoreBlogRequest $request): Blog
    {
        $blog = $this->run(new StoreBlogJob($request->validated()));

        $this->runInQueue(new NotifyViaEmailJob($blog), 'emails');

        return $blog;
    }
}
```

Every command accepts `--force` to overwrite an existing file. Route loading and its prefixes are controlled by `config/better-laravel.php` (`enable_routes`, `web_routes_prefix`, `api_routes_prefix`). See the [documentation](https://laranex.vercel.app/better-laravel) for the principles behind modules, domains, features, operations and jobs.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Nay Thu Khant](https://github.com/NayThuKhant)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
