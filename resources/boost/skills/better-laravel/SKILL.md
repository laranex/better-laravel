---
name: better-laravel-development
description: Build scalable Laravel applications using Better Laravel's modular, job-driven architecture with Features, Domains, Modules, Jobs, Operations, and more.
---

# Better Laravel Development

Use this skill when building Laravel applications with the Better Laravel package.

## Architecture Overview

Better Laravel provides a structured architecture built on:
- **Modular Architecture** - Organize code into Modules and Domains
- **Job-Driven Architecture** - Business logic lives in Jobs, orchestrated by Features
- **Clean Code Architecture** - Separation of concerns via Controllers, Features, Operations, Jobs
- **Domain-Driven Architecture** - Sharable Domains contain Jobs and Requests

## Key Concepts

### Domain (Sharable Globally)
Contains Jobs and Requests that can be consumed from any Module.
- Jobs: Business logic, handle Models, provide data
- Requests: Validation and authorization

### Module (Not Sharable)
Contains HTTP layer and Features for single-purpose use.
- Controllers: Serve Features, return HTTP responses
- Features: Validate requests, run Jobs/Operations, map response

### Feature
Orchestrates the business flow:
1. Receives validated Request
2. Runs Jobs/Operations
3. Maps data to response
4. Returns HTTP Response to Controller

### Operation
Optional reusable component that groups Jobs for use across multiple Features.

### Artisan Commands

```bash
# Generate a Controller
php artisan better:controller {name} {module}

# Generate a Feature
php artisan better:feature {name} {module}

# Generate a Job
php artisan better:job {name} {domain}

# Generate an Operation
php artisan better:operation {name} {module}

# Generate a Request
php artisan better:request {name} {domain}

# Generate a Route
php artisan better:route {name} {versionOrDirectory} --api
```

## Usage Examples

### Controller with Feature
```php
use App\Modules\BlogModule\Features\StoreBlogFeature;
use Laranex\BetterLaravel\Cores\Controller;

class BlogController extends Controller
{
    public function store()
    {
        return $this->serve(new StoreBlogFeature());
    }
}
```

### Feature with Job
```php
use App\Domains\Blog\Jobs\StoreBlogJob;
use App\Domains\Blog\Requests\StoreBlogRequest;
use Laranex\BetterLaravel\Cores\Feature;

class StoreBlogFeature extends Feature
{
    public function handle(StoreBlogRequest $request): Blog
    {
        return $this->run(new StoreBlogJob($request->validated()));
    }
}
```

### Feature with Queue Job
```php
use App\Domains\Blog\Jobs\StoreBlogJob;
use App\Domains\Blog\Jobs\NotifyViaEmailJob;
use Laranex\BetterLaravel\Cores\Feature;

class StoreBlogFeature extends Feature
{
    public function handle(StoreBlogRequest $request): Blog
    {
        $blog = $this->run(new StoreBlogJob($request->validated()));
        $this->runInQueue(new NotifyViaEmailJob($blog));
        return $blog;
    }
}
```

### Operation with Multiple Jobs
```php
use App\Domains\Blog\Jobs\NotifyViaEmailJob;
use App\Domains\Blog\Jobs\NotifyViaPushNotificationJob;
use Laranex\BetterLaravel\Cores\Operation;

class NotifySubscribersOperation extends Operation
{
    public function handle(): void
    {
        $this->run(new NotifyViaEmailJob($this->payload));
        $this->run(new NotifyViaPushNotificationJob($this->payload));
    }
}
```

### Request Validation
```php
use Laranex\BetterLaravel\Cores\Request;

class StoreBlogRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }
}
```

## File Structure

```
app/
├── Domains/           # Sharable across modules
│   └── Blog/
│       ├── Jobs/
│       │   └── StoreBlogJob.php
│       └── Requests/
│           └── StoreBlogRequest.php
└── Modules/           # Single-purpose modules
    └── BlogModule/
        ├── Features/
        │   └── StoreBlogFeature.php
        ├── Operations/
        │   └── NotifySubscribersOperation.php
        └── Http/Controllers/
            └── BlogController.php
```
