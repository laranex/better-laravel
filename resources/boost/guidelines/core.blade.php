# Better Laravel

Better Laravel is a Laravel package for building scalable applications using modular, job-driven, clean code, and domain-driven architecture.

## Architecture

- **Modular Architecture** - Organize code into Modules (not sharable) and Domains (sharable globally)
- **Job-Driven Architecture** - Business logic lives in Jobs, orchestrated by Features
- **Feature** - Validates requests, runs Jobs/Operations, maps data to response
- **Operation** - Optional reusable group of Jobs for use across multiple Features
- **Controller** - Serves Features and returns HTTP responses

## Artisan Commands

```bash
php artisan better:controller {name} {module}
php artisan better:feature {name} {module}
php artisan better:job {name} {domain}
php artisan better:operation {name} {module}
php artisan better:request {name} {domain}
php artisan better:route {name} {versionOrDirectory} --api
```

## Usage

### Controller Serving a Feature

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

### Feature Running Jobs

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

### Feature Running Queue Jobs

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

### Operation Running Multiple Jobs

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

### Job Structure

```php
use Laranex\BetterLaravel\Cores\Job;

class StoreBlogJob extends Job
{
    private array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        // Business logic here
    }
}
```

### Queueable Job

```php
use Laranex\BetterLaravel\Cores\QueueableJob;

class NotifyViaEmailJob extends QueueableJob
{
    private array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        // Queue logic here
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
