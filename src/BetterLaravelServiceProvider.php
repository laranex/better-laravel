<?php

namespace Laranex\BetterLaravel;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laranex\BetterLaravel\Commands\ControllerMakeCommand;
use Laranex\BetterLaravel\Commands\FeatureMakeCommand;
use Laranex\BetterLaravel\Commands\JobMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BetterLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('better-laravel')
            ->hasConfigFile()
            ->hasCommands([
                ControllerMakeCommand::class,
                FeatureMakeCommand::class,
                JobMakeCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->registerRoutes();
    }

    public function registerRoutes(): void
    {
        $webRoutes = File::glob(base_path('routes/web/*.php'));
        $apiRoutes = File::glob(base_path('routes/api/*.php'));

        foreach ($webRoutes as $route) {
            Route::middleware('web')
                ->group($route);
        }

        foreach ($apiRoutes as $route) {
            Route::prefix('api')
                ->middleware('api')
                ->group($route);
        }
    }
}
