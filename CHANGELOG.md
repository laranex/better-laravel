# Changelog

All notable changes to `better-laravel` will be documented in this file.

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
