---
name: better-laravel-development
description: >
  Structure a Laravel app into modules (controllers, features, operations) and domains (jobs, requests) with laranex/better-laravel, generate them with the better:* artisan commands and let the package load routes/web and routes/api.
license: MIT
metadata:
  author: Nay Thu Khant
---

# Better Laravel

Use this skill when a Laravel application uses `laranex/better-laravel` (classes under `App\Modules\*Module` and `App\Domains\*`, base classes from `Laranex\BetterLaravel\Cores`).

## Primary Goal

- keep the flow Controller → Feature → Job/Operation and generate every unit with the package commands instead of hand-writing files

## Workflow

### 1. Generate units

Run the commands with `--no-interaction`; every one accepts `--force` to overwrite an existing file and exits `1` when generation fails.

- `php artisan better:route {route} {versionOrDirectory?} [--api]` → `routes/web/{dir}/{routes}.php` or `routes/api/{dir}/{routes}.php` (name is pluralized kebab-case)
- `php artisan better:controller {controller} {module}` → `app/Modules/{Module}Module/Http/Controllers/{Name}Controller.php`
- `php artisan better:feature {feature} {module}` → `app/Modules/{Module}Module/Features/{Name}Feature.php`
- `php artisan better:operation {operation} {module}` → `app/Modules/{Module}Module/Operations/{Name}Operation.php`
- `php artisan better:request {request} {domain}` → `app/Domains/{Domain}/Requests/{Name}Request.php`
- `php artisan better:job {job} {domain} [--queue]` → `app/Domains/{Domain}/Jobs/{Name}Job.php` (`--queue` extends `QueueableJob`)

Suffixes (`Controller`, `Feature`, `Job`, …) and the `Module` suffix are added automatically; `.php` is stripped. Names must not contain `/` or `\` (nested names such as `Blog/CreatePost` exit `1`); use `versionOrDirectory` for route subdirectories.

### 2. Wire them

- controllers extend `Laranex\BetterLaravel\Cores\Controller` and return `$this->serve(new SomeFeature(...))`
- features extend `Cores\Feature`, type-hint the `Cores\Request` subclass in `handle()`, call `$this->run(new SomeJob(...))` or `$this->run(new SomeOperation(...))` and `$this->runInQueue(new SomeQueueableJob(...), 'queue-name')`
- operations extend `Cores\Operation` and only run jobs; jobs extend `Cores\Job` (or `Cores\QueueableJob`) and expose `handle()`
- pass constructor arguments: `run()`, `runInQueue()` and `serve()` take instances, not class names
- modules are not shared across the app; domains (jobs, requests) are

### 3. Routes and configuration

- every PHP file under `routes/web` and `routes/api` is loaded automatically with the `web` / `api` middleware groups and the prefixes from `config/better-laravel.php` (`web_routes_prefix` default `''`, `api_routes_prefix` default `'api'`)
- set `BETTER_LARAVEL_ENABLE_ROUTES=false` (or `enable_routes`) to register the files yourself
- publish only when needed: `php artisan vendor:publish --tag="better-laravel-config"`, `--tag="better-laravel-stubs"` (custom generator stubs are read from `resources/stubs/vendor/better-laravel`; `route.php.stub` gets `{{prefix}}`, e.g. `v1/blogs`), `--tag="better-laravel-views"`

## Rules, References, and Templates

- no additional resource files for this skill

## Examples

- `php artisan better:route blog v1 --api --no-interaction` then `php artisan better:controller Blog Blog --no-interaction` and `php artisan better:feature StoreBlog Blog --no-interaction`
- `return $this->serve(new StoreBlogFeature);` in the controller; `$blog = $this->run(new StoreBlogJob($request->validated())); $this->runInQueue(new NotifyViaEmailJob($blog));` in the feature

## Anti-patterns

- do not pass class-name strings to `serve()`, `run()` or `runInQueue()`; instantiate the unit
- do not put business logic in controllers or features; it belongs in jobs
- do not require `routes/web/*.php` files manually while `enable_routes` is on, the routes would be registered twice
- do not call `runInQueue()` with a plain `Job`; it must extend `QueueableJob`
