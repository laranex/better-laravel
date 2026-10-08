# Changelog

All notable changes to `better-laravel` will be documented in this file.

## v4.0.0 - Unreleased

Version 3 was skipped so that every Laranex package shares the same major version.

### Changed
- Requires PHP 8.1+ and supports Laravel 10 through 13.
- Rebuilt on the official Laravel package skeleton (Pest, PHPStan level 7, Pint, Testbench workbench, GitHub Actions matrix) with a full test suite covering every command, the service provider and the bus traits.
- `spatie/laravel-package-tools` was dropped; `BetterLaravelServiceProvider` is a plain `Illuminate\Support\ServiceProvider`. The publish tags are unchanged (`better-laravel-config`, `better-laravel-views`, `better-laravel-stubs`) and a `better-laravel` tag now publishes everything at once.
- The package only requires the `illuminate/*` components it uses instead of `illuminate/contracts` alone.
- Route files are registered during `boot()` instead of `register()`, so host configuration (including cached config) is honoured.
- The `better:*` commands now exit with code `1` when generation fails (for example when the file exists and `--force` was not given) instead of always returning `0`.
- `Commands\BaseCommand` is abstract and `Generators\Generator::getStubContents()` implementations share one stub lookup; a `JobGenerator::getStubContents()` call now defaults to the synchronous stub.
- `Bus\UnitDispatcher::runInQueue()` is typed to return `Illuminate\Foundation\Bus\PendingDispatch` and no longer wraps the call in a dead `try/catch`.
- `Laranex\BetterLaravel\Str` no longer overrides `studly()`; all helper parameters are typed `string`.
- `BetterLaravel::getAllFilesOfADirectory()` returns the files sorted.
- Every source file declares `strict_types`.

### Fixed
- The `job.queueable.php.stub` declared `__construct(): void`, which is a fatal error in generated jobs.
- The disabled-routes warning named the wrong config key (`better-myanmar.enable_routes`); it now says `better-laravel.enable_routes`.
- Generated route files import the `Route` facade explicitly.

### Upgrading
- Upgrade to PHP 8.1 or higher (8.2 is no longer the floor) and run `composer require laranex/better-laravel:^4.0`.
- If you check the exit code of `better:*` commands in scripts, a failed generation is now `1`.
- If you extended `Commands\BaseCommand` directly, it is now abstract: extend it from a concrete command rather than instantiating it.
- If you called `Str::studly()` through `Laranex\BetterLaravel\Str` with a second argument, pass only the value (Laravel 13 adds its own `$normalize` parameter).
- If you published the stubs before, republish them with `php artisan vendor:publish --tag="better-laravel-stubs" --force` to pick up the fixed queueable job stub.
- Nothing else changes: the base classes (`Cores\Controller`, `Feature`, `Operation`, `Job`, `QueueableJob`, `Request`), the `serve()`, `run()` and `runInQueue()` methods, the command signatures, the config keys and the generated file locations are the same as in v2.

---

## v2.0.0 - Mar 25, 2025

### Breaking Changes

> ⚠️ **Passing string class names to unit dispatch methods (`run()`, `runInQueue()`, and `serve()`) is now completely removed. Use direct instantiation instead.**

**⚠️ Removed Pattern**
```php
// ❌ Removed - will no longer work
$this->serve(ProcessOrderFeature::class, ['orderId' => 123]);
$this->run(ProcessOrderJob::class, ['orderId' => 123]);
$this->runInQueue(SendEmailJob::class, ['email' => 'user@example.com']);
```

**✅ Use Direct Instantiation**
```php
// ✅ Use direct instantiation
$this->serve(new ProcessOrderFeature(orderId: 123));
$this->run(new ProcessOrderJob(orderId: 123));
$this->runInQueue(new SendEmailJob(email: 'user@example.com'));
```

### Other Changes

**Laravel 13 Support** - Package now supports Laravel 13 alongside existing 10, 11, and 12 support.

**PHP Minimum Version** bumped from 8.1 to 8.2.

### What You Need To Do
If you're using PHP 8.1, upgrade to PHP 8.2 or higher as the minimum requirement has changed.

---

## v1.1.2 - Feb 03, 2025

### What's Changed

Passing string class names to unit dispatch methods is now deprecated and will be **completely removed** in a future version. A deprecation warning will be triggered when using string class names with the `run()`, `runInQueue()`, and `serve()` methods.

**⚠️ Deprecated Pattern**
```php
// ❌ Deprecated - will trigger a warning
$this->serve(ProcessOrderFeature::class, ['orderId' => 123]);
$this->run(ProcessOrderJob::class, ['orderId' => 123]);
$this->runInQueue(SendEmailJob::class, ['email' => 'user@example.com']);
```

**✅ Recommended Pattern**
```php
// ✅ Use direct instantiation instead
$this->serve(new ProcessOrderFeature(orderId: 123));
$this->run(new ProcessOrderJob(orderId: 123));
$this->runInQueue(new SendEmailJob(email: 'user@example.com'));
```

### Why This Change?
- **Better IDE Support**: Direct instantiation provides better autocomplete and type checking
- **Improved Clarity**: Makes it clear what arguments are being passed to the unit
- **Type Safety**: Catches errors at instantiation time rather than runtime
- **Modern PHP**: Aligns with PHP 8+ best practices

### Migration Timeline
- **Now**: Deprecation warnings are triggered when using string class names
- **Future Version**: String class names will be **completely removed** from dispatch methods

### What You Need To Do
Update your codebase to instantiate units directly instead of passing class name strings. The deprecation warning will help you identify all locations that need updating.

### Example Migration

**Before:**
```php
class OrderController extends Controller
{
    public function store(Request $request)
    {
        return $this->serve(CreateOrderFeature::class, [
            'userId' => $request->user()->id,
            'items' => $request->input('items')
        ]);
    }
}

class CreateOrderFeature extends Feature
{
    public function handle()
    {
        $order = $this->run(CreateOrderJob::class, [$this->userId, $this->items]);
        $this->runInQueue(SendEmailJob::class, [$order->id]);
        return $order;
    }
}
```

**After:**
```php
class OrderController extends Controller
{
    public function store(Request $request)
    {
        return $this->serve(new CreateOrderFeature(
            userId: $request->user()->id,
            items: $request->input('items')
        ));
    }
}

class CreateOrderFeature extends Feature
{
    public function handle()
    {
        $order = $this->run(new CreateOrderJob($this->userId, $this->items));
        $this->runInQueue(new SendOrderConfirmationEmailJob($order->id));
        return $order;
    }
}
```

---

## v1.1.1 - Oct 22, 2025

### What's Changed

**Add Laravel 12 support** - Package now supports Laravel 12 alongside existing 10 and 11 support.
