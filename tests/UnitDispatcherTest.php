<?php

use Illuminate\Support\Facades\Bus;
use Laranex\BetterLaravel\Tests\Testables\Dispatcher;
use Laranex\BetterLaravel\Tests\Testables\TestJob;

$testJob = new TestJob('Better Laravel');

it('can dispatch a job synchronously', function () use ($testJob) {
    $message = (new Dispatcher())->run($testJob);
    expect($message)->toBe('Hello! Better Laravel');
});

it('can dispatch a job asynchronously, & on default queue', function () use ($testJob) {
    Bus::fake();
    (new Dispatcher())->runInQueue($testJob);
    Bus::assertDispatched(TestJob::class);

    expect(Bus::dispatched(TestJob::class)->first()->queue)->toBe('default');
});

it('can dispatch a job asynchronously, & on given queue', function () use ($testJob) {
    Bus::fake();
    (new Dispatcher())->runInQueue($testJob, [], 'queue');
    Bus::assertDispatched(TestJob::class);

    expect(Bus::dispatched(TestJob::class)->first()->queue)->toBe('queue');
});
