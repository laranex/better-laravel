<?php

declare(strict_types=1);

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Queue;
use Laranex\BetterLaravel\Cores\Controller;
use Laranex\BetterLaravel\Cores\Feature;
use Laranex\BetterLaravel\Cores\Job;
use Laranex\BetterLaravel\Cores\Operation;
use Laranex\BetterLaravel\Cores\QueueableJob;

final class SumJob extends Job
{
    public function __construct(private readonly int $a, private readonly int $b) {}

    public function handle(): int
    {
        return $this->a + $this->b;
    }
}

final class SendEmailJob extends QueueableJob
{
    public function __construct(public readonly string $email) {}

    public function handle(): void {}
}

final class DoubleOperation extends Operation
{
    public function __construct(private readonly int $value) {}

    public function handle(): int
    {
        return $this->run(new SumJob($this->value, $this->value));
    }
}

final class CheckoutFeature extends Feature
{
    /**
     * @return array<string, int|PendingDispatch>
     */
    public function handle(): array
    {
        return [
            'sum' => $this->run(new SumJob(2, 3)),
            'double' => $this->run(new DoubleOperation(4)),
            'queued' => $this->runInQueue(new SendEmailJob('user@example.com'), 'emails'),
        ];
    }
}

final class CheckoutController extends Controller
{
    public function store(): mixed
    {
        return $this->serve(new CheckoutFeature);
    }
}

it('runs jobs and operations synchronously and returns their results', function () {
    $feature = new Feature;
    $operation = new Operation;

    expect($feature->run(new SumJob(1, 2)))->toBe(3)
        ->and($operation->run(new SumJob(2, 2)))->toBe(4)
        ->and($feature->run(new DoubleOperation(5)))->toBe(10);
});

it('pushes queueable jobs onto the requested queue', function () {
    Queue::fake();

    $dispatch = (new Feature)->runInQueue(new SendEmailJob('user@example.com'), 'emails');
    expect($dispatch)->toBeInstanceOf(PendingDispatch::class);
    unset($dispatch);

    (new Operation)->runInQueue(new SendEmailJob('ops@example.com'));

    Queue::assertPushedOn('emails', SendEmailJob::class, fn (SendEmailJob $job): bool => $job->email === 'user@example.com');
    Queue::assertPushedOn('default', SendEmailJob::class, fn (SendEmailJob $job): bool => $job->email === 'ops@example.com');
    Queue::assertPushed(SendEmailJob::class, 2);
});

it('serves a feature from a controller and returns what the feature returns', function () {
    Queue::fake();

    $result = (new CheckoutController)->store();

    expect($result)->toBeArray()
        ->and($result['sum'])->toBe(5)
        ->and($result['double'])->toBe(8);

    unset($result);

    Queue::assertPushedOn('emails', SendEmailJob::class);
});
