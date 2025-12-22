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

$featureCommand = 'better:feature Sample Sample';
$featureCommandWithDry = 'better:feature Sample Sample -D';
$featureCommandWithForce = 'better:feature Sample Sample -F';
$featureCommandWithTest = 'better:feature Sample Sample -T';
$featureCommandWithTestDry = 'better:feature Sample Sample -T -D';
$featureCommandWithTestForce = 'better:feature Sample Sample -T -F';

$featureFilePath = '\app\Modules/SampleModule/Features/SampleFeature.php';
$featureTestFilePath = '\tests/Feature/Modules/SampleModule/SampleFeatureTest.php';

it('generates the feature', function () use ($featureCommand, $featureFilePath) {
    $this->artisan($featureCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the feature file path in dry run mode', function () use ($featureCommandWithDry, $featureFilePath) {
    $this->artisan($featureCommandWithDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;

    expect(File::exists($path))->toBeFalse();
});

it('generates the feature in force mode', function () use ($featureCommand, $featureCommandWithForce, $featureFilePath) {
    $this->artisan($featureCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;

    expect(File::exists($path))->toBeTrue();

    $this->artisan($featureCommandWithForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->assertExitCode(0);
});

it('throws exception when the feature file exists', function () use ($featureCommand, $featureFilePath) {
    $this->artisan($featureCommand)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;
    expect(File::exists($path))->toBeTrue();

    $this->artisan($featureCommand)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($featureFilePath.' already exists!'))
        ->assertExitCode(0);
});

it('generates the feature and test file', function () use ($featureCommandWithTest, $featureFilePath, $featureTestFilePath) {

    $this->artisan($featureCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;

    expect(File::exists($path))->toBeTrue();
});

it('returns the feature and test file path in dry run mode', function () use ($featureCommandWithTestDry, $featureFilePath, $featureTestFilePath) {
    $this->artisan($featureCommandWithTestDry)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;
    expect(File::exists($path))->toBeFalse();

    $testPath = $this->tempPath.$featureTestFilePath;
    expect(File::exists($testPath))->toBeFalse();
});

it('generates the feature and test file in force mode', function () use ($featureCommandWithTest, $featureCommandWithTestForce, $featureFilePath, $featureTestFilePath) {
    $this->artisan($featureCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;
    expect(File::exists($path))->toBeTrue();

    $testPath = $this->tempPath.$featureTestFilePath;
    expect(File::exists($testPath))->toBeTrue();

    $this->artisan($featureCommandWithTestForce)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    expect(File::exists($path))->toBeTrue();
    expect(File::exists($testPath))->toBeTrue();
});

it('throws exception when the feature and test file exists', function () use ($featureCommandWithTest, $featureFilePath, $featureTestFilePath) {
    $this->artisan($featureCommandWithTest)
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureFilePath))
        ->expectsOutput(Decorator::getFileGeneratedOutput($featureTestFilePath))
        ->assertExitCode(0);

    $path = $this->tempPath.$featureFilePath;
    expect(File::exists($path))->toBeTrue();

    $testPath = $this->tempPath.$featureTestFilePath;
    expect(File::exists($testPath))->toBeTrue();

    $this->artisan($featureCommandWithTest)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput($featureFilePath.' already exists!'))
        ->assertExitCode(0);
});
