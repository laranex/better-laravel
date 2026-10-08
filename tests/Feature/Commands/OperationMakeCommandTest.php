<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('generates an operation inside a module', function () {
    $this->cleanUp(app_path('Modules/NotificationModule'));
    $path = app_path('Modules/NotificationModule/Operations/NotifySubscribersOperation.php');

    $this->artisan('better:operation', ['operation' => 'NotifySubscribersOperation', 'module' => 'Notification'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('namespace App\Modules\NotificationModule\Operations;')
        ->toContain('use Laranex\BetterLaravel\Cores\Operation;')
        ->toContain('class NotifySubscribersOperation extends Operation');
});

it('refuses to overwrite an existing operation unless forced', function () {
    $this->cleanUp(app_path('Modules/ReportModule'));

    $this->artisan('better:operation', ['operation' => 'buildReport', 'module' => 'report'])->assertExitCode(0);

    $this->artisan('better:operation', ['operation' => 'buildReport', 'module' => 'report'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('app/Modules/ReportModule/Operations/BuildReportOperation.php already exists!'))
        ->assertExitCode(1);

    $this->artisan('better:operation', ['operation' => 'buildReport', 'module' => 'report', '--force' => true])->assertExitCode(0);
});
