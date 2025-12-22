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

$featureTestCommand = 'better:test Sample Sample';
$featureTestCommandWithDry = 'better:test Sample Sample -D';
$featureTestCommandWithForce = 'better:test Sample Sample -F';
$featureTestFilePath = '\tests/Feature/Modules/SampleModule/SampleFeatureTest.php';

$jobTestCommand = 'better:test Sample Sample -J';
$jobTestCommandWithDry = 'better:test Sample Sample -J -D';
$jobTestCommandWithForce = 'better:test Sample Sample -J -F';
$jobTestFilePath = '\tests/Unit/Domains/Sample/SampleJobTest.php';

it('generates the test file for the feature', function () use ($featureTestCommand, $featureTestFilePath) {
    $this->artisan($featureTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureTestFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the test file path for the feature in dry run mode', function () use ($featureTestCommandWithDry, $featureTestFilePath) {
    $this->artisan($featureTestCommandWithDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureTestFilePath;

    expect(File::exists($path))->toBeFalse();
});

it('generates the test file for the feature in force mode', function () use ($featureTestCommand, $featureTestCommandWithForce, $featureTestFilePath) {
    $this->artisan($featureTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureTestFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($featureTestCommandWithForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);
});

it('throws exception when the test file exists for the feature', function () use ($featureTestCommand, $featureTestFilePath) {
    $this->artisan($featureTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureTestFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($featureTestCommand)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($featureTestFilePath.' already exists!'))
        ->assertExitCode(0);
});

it('generates the test file for the job', function () use ($jobTestCommand, $jobTestFilePath) {

    $this->artisan($jobTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobTestFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the test file path for the job in dry run mode', function () use ($jobTestCommandWithDry, $jobTestFilePath) {
    $this->artisan($jobTestCommandWithDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobTestFilePath;

    expect(File::exists($path))->toBeFalse();
});

it('generates the test file for the job in force mode', function () use ($jobTestCommand, $jobTestCommandWithForce, $jobTestFilePath) {
    $this->artisan($jobTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobTestFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($jobTestCommandWithForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);
});

it('throws exception when the test file exists for the job', function () use ($jobTestCommand, $jobTestFilePath) {
    $this->artisan($jobTestCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobTestFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($jobTestCommand)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($jobTestFilePath.' already exists!'))
        ->assertExitCode(0);
});
