---
name: better-laravel
description: >
  Structure a Laravel app into modules (controllers, features, operations) and domains (jobs, requests) with laranex/better-laravel, generate them with the better:* Artisan commands and let the package load routes/web and routes/api.
license: MIT
metadata:
  author: Nay Thu Khant
---

# Better Laravel

## When to use

Use this skill when a Laravel application uses `laranex/better-laravel`: classes live under `App\Modules\*Module` (controllers, features, operations) and `App\Domains\*` (jobs, requests) and extend the base classes in `Laranex\BetterLaravel\Cores`. Keep the flow Controller → Feature → Job/Operation and generate every unit with the package commands instead of writing the files by hand.

## Install

```bash
composer require laranex/better-laravel
```

Requires PHP 8.1+ and Laravel 10 to 13. The service provider and the `BetterLaravel` facade are auto-discovered.

## Configure

- Every PHP file under `routes/web` and `routes/api` is loaded automatically with the `web` / `api` middleware groups.
- `BETTER_LARAVEL_ENABLE_ROUTES` (default `true`): set to `false` to register the route files yourself.
- `BETTER_LARAVEL_WEB_ROUTES_PREFIX` (default empty) and `BETTER_LARAVEL_API_ROUTES_PREFIX` (default `api`): URI prefixes for the two folders.
- Publish only what you change: `php artisan vendor:publish --tag="better-laravel-config"`, `--tag="better-laravel-stubs"` (custom generator stubs are read from `resources/stubs/vendor/better-laravel`) or `--tag="better-laravel-views"`.

## Use

### Generate units

Run the commands with `--no-interaction`. Every command accepts `--force` to overwrite an existing file and exits `1` when the file exists or the name is invalid.

- `php artisan better:route {route} {versionOrDirectory?} [--api]` → `routes/web/{dir}/{routes}.php` or `routes/api/{dir}/{routes}.php` (the name is pluralized kebab-case)
- `php artisan better:controller {controller} {module}` → `app/Modules/{Module}Module/Http/Controllers/{Name}Controller.php`
- `php artisan better:feature {feature} {module}` → `app/Modules/{Module}Module/Features/{Name}Feature.php`
- `php artisan better:operation {operation} {module}` → `app/Modules/{Module}Module/Operations/{Name}Operation.php`
- `php artisan better:request {request} {domain}` → `app/Domains/{Domain}/Requests/{Name}Request.php`
- `php artisan better:job {job} {domain} [--queue]` → `app/Domains/{Domain}/Jobs/{Name}Job.php` (`--queue` extends `QueueableJob`)

Suffixes (`Controller`, `Feature`, `Job`, …) and the `Module` suffix are added automatically and `.php` is stripped. Names must not contain `/` or `\`; use `versionOrDirectory` for route subfolders.

```bash
php artisan better:route blog v1 --api --no-interaction
php artisan better:controller Blog Blog --no-interaction
php artisan better:feature StoreBlog Blog --no-interaction
php artisan better:job StoreBlog Blog --no-interaction
```

### Wire them together

- Controllers extend `Laranex\BetterLaravel\Cores\Controller` and return `$this->serve(new StoreBlogFeature)`.
- Features extend `Cores\Feature`, type-hint their `Cores\Request` subclass in `handle()` and call `$this->run(new SomeJob(...))`, `$this->run(new SomeOperation(...))` or `$this->runInQueue(new SomeQueueableJob(...), 'queue-name')`.
- Operations extend `Cores\Operation` and only run jobs. Jobs extend `Cores\Job` (or `Cores\QueueableJob`) and do the work in `handle()`.
- `serve()`, `run()` and `runInQueue()` take instances; pass data through the constructor.

```php
// app/Modules/BlogModule/Features/StoreBlogFeature.php
public function handle(StoreBlogRequest $request): mixed
{
    $blog = $this->run(new StoreBlogJob($request->validated()));
    $this->runInQueue(new NotifyFollowersJob($blog), 'emails');

    return $blog;
}
```

## Test your app

- Call the route and assert the response; features and jobs run synchronously through `run()`.
- Use `Queue::fake()` and `Queue::assertPushedOn('emails', NotifyFollowersJob::class)` for `runInQueue()`. Avoid `Bus::fake()` here: it also swallows the synchronous `run()` and `serve()` calls.
- Unit-test a job by calling `(new StoreBlogJob($data))->handle()` directly.

## Avoid

- Passing class-name strings to `serve()`, `run()` or `runInQueue()`; instantiate the unit.
- Business logic in controllers or features; it belongs in jobs.
- Requiring `routes/web/*.php` files manually while `enable_routes` is on; the routes would be registered twice.
- Calling `runInQueue()` with a plain `Job`; it must extend `QueueableJob`.
