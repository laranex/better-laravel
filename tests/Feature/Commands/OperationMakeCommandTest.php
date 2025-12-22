<?php

use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;

beforeEach(function () {
    $this->tempPath = sys_get_temp_dir().'/better-laravel-tests';

    File::deleteDirectory($this->tempPath);
    File::ensureDirectoryExists($this->tempPath);

    $this->app->setBasePath($this->tempPath);
});

afterEach(function () {
    File::deleteDirectory($this->tempPath);
});

$operationCommand = 'better:operation Sample Sample';
$operationCommandWithDry = 'better:operation Sample Sample -D';
$operationCommandWithForce = 'better:operation Sample Sample -F';

$operationFilePath = '\app\Modules/SampleModule/Operations/SampleOperation.php';

it('generates the operation', function () use ($operationCommand, $operationFilePath) {
    $this->artisan($operationCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($operationFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$operationFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the operation file path in dry run mode', function () use ($operationCommandWithDry, $operationFilePath) {
    $this->artisan($operationCommandWithDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($operationFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$operationFilePath;

    expect(File::exists($path))->toBeFalse();
});

it('generates the operation in force mode', function () use ($operationCommand, $operationCommandWithForce, $operationFilePath) {
    $this->artisan($operationCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($operationFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$operationFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($operationCommandWithForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($operationFilePath))
        ->assertExitCode(0);
});

it('throws exception when the operation file exists', function () use ($operationCommand, $operationFilePath) {
    $this->artisan($operationCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($operationFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$operationFilePath;
    expect(File::exists($path))->toBeTrue();

    $this->artisan($operationCommand)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($operationFilePath.' already exists!'))
        ->assertExitCode(0);
});
