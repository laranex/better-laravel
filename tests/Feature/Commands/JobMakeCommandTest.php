<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('generates a synchronous job inside a domain', function () {
    $this->cleanUp(app_path('Domains/Blog'));
    $path = app_path('Domains/Blog/Jobs/StoreBlogJob.php');

    $this->artisan('better:job', ['job' => 'StoreBlog', 'domain' => 'blog'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('namespace App\Domains\Blog\Jobs;')
        ->toContain('use Laranex\BetterLaravel\Cores\Job;')
        ->toContain('class StoreBlogJob extends Job')
        ->not->toContain('QueueableJob');
});

it('generates a queueable job with the --queue option', function () {
    $this->cleanUp(app_path('Domains/Mail'));
    $path = app_path('Domains/Mail/Jobs/SendWelcomeMailJob.php');

    $this->artisan('better:job', ['job' => 'sendWelcomeMail', 'domain' => 'Mail', '--queue' => true])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('use Laranex\BetterLaravel\Cores\QueueableJob;')
        ->toContain('class SendWelcomeMailJob extends QueueableJob')
        ->toContain('public function __construct()')
        ->not->toContain('__construct(): void');
});

it('refuses to overwrite an existing job unless forced', function () {
    $this->cleanUp(app_path('Domains/Billing'));

    $this->artisan('better:job', ['job' => 'ChargeCard', 'domain' => 'Billing'])->assertExitCode(0);

    $this->artisan('better:job', ['job' => 'ChargeCardJob', 'domain' => 'billing'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('app/Domains/Billing/Jobs/ChargeCardJob.php already exists!'))
        ->assertExitCode(1);

    $this->artisan('better:job', ['job' => 'ChargeCard', 'domain' => 'Billing', '--queue' => true, '--force' => true])->assertExitCode(0);

    expect(file_get_contents(app_path('Domains/Billing/Jobs/ChargeCardJob.php')))->toContain('extends QueueableJob');
});
