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

$jobCommand = 'better:job Sample Sample';
$jobCommandWithDry = 'better:job Sample Sample -D';
$jobCommandWithForce = 'better:job Sample Sample -F';
$jobCommandWithTest = 'better:job Sample Sample -T';
$jobCommandWithTestDry = 'better:job Sample Sample -T -D';
$jobCommandWithTestForce = 'better:job Sample Sample -T -F';

$jobFilePath = '\app\Domains/Sample/Jobs/SampleJob.php';
$jobTestFilePath = '\tests/Unit/Domains/Sample/SampleJobTest.php';

it('generates the job', function () use ($jobCommand, $jobFilePath) {
    $this->artisan($jobCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the job file path in dry run mode', function () use ($jobCommandWithDry, $jobFilePath) {
    $this->artisan($jobCommandWithDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;

    expect(File::exists($path))->toBeFalse();
});

it('generates the job in force mode', function () use ($jobCommand, $jobCommandWithForce, $jobFilePath) {
    $this->artisan($jobCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($jobCommandWithForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->assertExitCode(0);
});

it('throws exception when the job file exists', function () use ($jobCommand, $jobFilePath) {
    $this->artisan($jobCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;
    expect(File::exists($path))->toBeTrue();

    $this->artisan($jobCommand)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($jobFilePath.' already exists!'))
        ->assertExitCode(0);
});

it('generates the job and test file', function () use ($jobCommandWithTest, $jobFilePath, $jobTestFilePath) {
    $this->artisan($jobCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the job and test file path in dry run mode', function () use ($jobCommandWithTestDry, $jobFilePath, $jobTestFilePath) {
    $this->artisan($jobCommandWithTestDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;
    expect(File::exists($path))->toBeFalse();

    $testPath = $this->tempPath.$jobTestFilePath;
    expect(File::exists($testPath))->toBeFalse();
});

it('generates the job and test file in force mode', function () use ($jobCommandWithTest, $jobCommandWithTestForce, $jobFilePath, $jobTestFilePath) {
    $this->artisan($jobCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;
    expect(File::exists($path))->toBeTrue();

    $testPath = $this->tempPath.$jobTestFilePath;
    expect(File::exists($testPath))->toBeTrue();

    $this->artisan($jobCommandWithTestForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    expect(File::exists($path))->toBeTrue();
    expect(File::exists($testPath))->toBeTrue();
});

it('throws exception when the job and test file exists', function () use ($jobCommandWithTest, $jobFilePath, $jobTestFilePath) {
    $this->artisan($jobCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($jobTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$jobFilePath;
    expect(File::exists($path))->toBeTrue();

    $testPath = $this->tempPath.$jobTestFilePath;
    expect(File::exists($testPath))->toBeTrue();

    $this->artisan($jobCommandWithTest)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($jobFilePath.' already exists!'))
        ->assertExitCode(0);
});
