<?php

namespace Laranex\BetterLaravel;

use App;
use Illuminate\Support\Facades\Route;
use Laranex\BetterLaravel\Commands\ControllerMakeCommand;
use Laranex\BetterLaravel\Commands\FeatureMakeCommand;
use Laranex\BetterLaravel\Commands\JobMakeCommand;
use Laranex\BetterLaravel\Commands\OperationMakeCommand;
use Laranex\BetterLaravel\Commands\RouteMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BetterLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('better-laravel')
            ->hasConfigFile()
            ->hasCommands([
                RouteMakeCommand::class,
                ControllerMakeCommand::class,
                FeatureMakeCommand::class,
                OperationMakeCommand::class,
                JobMakeCommand::class,
            ])->hasViews('better-laravel');
    }

    public function packageRegistered(): void
    {
        if (config('better-laravel.enable_routes') && ! App::routesAreCached()) {
            $this->registerRoutes();
        }
    }

    public function registerRoutes(): void
    {
        $webRoutes = BetterLaravel::getAllFilesOfADirectory(base_path('routes/web'), 'php');
        $apiRoutes = BetterLaravel::getAllFilesOfADirectory(base_path('routes/api'), 'php');

        $webRoutesPrefix = config('better-laravel.web_routes_prefix');
        $apiRoutesPrefix = config('better-laravel.api_routes_prefix');

        foreach ($webRoutes as $route) {
            Route::middleware('web')
                ->prefix($webRoutesPrefix)
                ->group($route);
        }

        foreach ($apiRoutes as $route) {
            Route::middleware('api')
                ->prefix($apiRoutesPrefix)
                ->group($route);
        }
    }
}
