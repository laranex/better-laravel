<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Laranex\BetterLaravel\BetterLaravelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Paths (relative to the skeleton base path) that a test generated and wants removed again.
     *
     * @var array<int, string>
     */
    protected array $generatedPaths = [];

    private static bool $basePathPrepared = false;

    protected function getPackageProviders($app): array
    {
        return [
            BetterLaravelServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));

        $this->useIsolatedBasePath($app);
    }

    protected function tearDown(): void
    {
        $files = new Filesystem;

        foreach ($this->generatedPaths as $path) {
            $files->isDirectory($path) ? $files->deleteDirectory($path) : $files->delete($path);
        }

        $this->generatedPaths = [];

        parent::tearDown();
    }

    /**
     * Remember a generated path so it is deleted after the test; also deletes any leftover from an earlier run.
     */
    protected function cleanUp(string $path): string
    {
        $files = new Filesystem;
        $files->isDirectory($path) ? $files->deleteDirectory($path) : $files->delete($path);

        $this->generatedPaths[] = $path;

        return $path;
    }

    /**
     * Point the application at a base path owned by this test process and copy the fixture route files into it.
     *
     * Generated modules, domains, routes and published stubs land there, so parallel test processes never see
     * (or require) each other's files and the Testbench skeleton stays untouched.
     */
    private function useIsolatedBasePath(Application $app): void
    {
        $files = new Filesystem;
        $basePath = sys_get_temp_dir().'/better-laravel-tests/'.getmypid();

        if (! self::$basePathPrepared) {
            $files->deleteDirectory($basePath);
            self::$basePathPrepared = true;
        }

        $app->setBasePath($basePath);

        foreach ($files->allFiles(__DIR__.'/Fixtures/routes') as $fixture) {
            $target = $basePath.'/routes/'.$fixture->getRelativePathname();

            $files->ensureDirectoryExists(dirname($target));
            $files->copy($fixture->getPathname(), $target);
        }
    }
}
