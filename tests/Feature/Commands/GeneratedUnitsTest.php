<?php

declare(strict_types=1);

use App\Domains\Generated\Jobs\UntouchedJob;
use App\Domains\Generated\Jobs\UntouchedQueuedJob;
use App\Domains\Generated\Requests\UntouchedRequest;
use App\Modules\GeneratedModule\Features\UntouchedFeature;
use App\Modules\GeneratedModule\Http\Controllers\UntouchedController;
use App\Modules\GeneratedModule\Operations\UntouchedOperation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Queue;
use Laranex\BetterLaravel\Cores\Controller;
use Laranex\BetterLaravel\Cores\Feature;
use Laranex\BetterLaravel\Cores\Job;
use Laranex\BetterLaravel\Cores\Operation;
use Laranex\BetterLaravel\Cores\QueueableJob;

it('generates units that load and run without any edits', function () {
    $this->cleanUp(app_path('Modules/GeneratedModule'));
    $this->cleanUp(app_path('Domains/Generated'));

    $this->artisan('better:controller', ['controller' => 'Untouched', 'module' => 'Generated'])->assertExitCode(0);
    $this->artisan('better:feature', ['feature' => 'Untouched', 'module' => 'Generated'])->assertExitCode(0);
    $this->artisan('better:operation', ['operation' => 'Untouched', 'module' => 'Generated'])->assertExitCode(0);
    $this->artisan('better:request', ['request' => 'Untouched', 'domain' => 'Generated'])->assertExitCode(0);
    $this->artisan('better:job', ['job' => 'Untouched', 'domain' => 'Generated'])->assertExitCode(0);
    $this->artisan('better:job', ['job' => 'UntouchedQueued', 'domain' => 'Generated', '--queue' => true])->assertExitCode(0);

    foreach ([
        'Modules/GeneratedModule/Http/Controllers/UntouchedController.php',
        'Modules/GeneratedModule/Features/UntouchedFeature.php',
        'Modules/GeneratedModule/Operations/UntouchedOperation.php',
        'Domains/Generated/Requests/UntouchedRequest.php',
        'Domains/Generated/Jobs/UntouchedJob.php',
        'Domains/Generated/Jobs/UntouchedQueuedJob.php',
    ] as $file) {
        require_once app_path($file);
    }

    $controller = new UntouchedController;
    $feature = new UntouchedFeature;
    $operation = new UntouchedOperation;
    $job = new UntouchedJob;
    $queuedJob = new UntouchedQueuedJob;
    $request = new UntouchedRequest;

    expect($controller)->toBeInstanceOf(Controller::class)
        ->and($feature)->toBeInstanceOf(Feature::class)
        ->and($operation)->toBeInstanceOf(Operation::class)
        ->and($job)->toBeInstanceOf(Job::class)
        ->and($queuedJob)->toBeInstanceOf(QueueableJob::class)
        ->and($request)->toBeInstanceOf(FormRequest::class)
        ->and($request->authorize())->toBeFalse()
        ->and($request->rules())->toBe([])
        ->and($controller->serve($feature))->toBeNull()
        ->and($feature->run($operation))->toBeNull()
        ->and($feature->run($job))->toBeNull();

    Queue::fake();

    $feature->runInQueue($queuedJob, 'generated');

    Queue::assertPushedOn('generated', UntouchedQueuedJob::class);
});
