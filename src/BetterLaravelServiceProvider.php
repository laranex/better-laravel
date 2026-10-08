<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laranex\BetterLaravel\Commands\ControllerMakeCommand;
use Laranex\BetterLaravel\Commands\FeatureMakeCommand;
use Laranex\BetterLaravel\Commands\JobMakeCommand;
use Laranex\BetterLaravel\Commands\OperationMakeCommand;
use Laranex\BetterLaravel\Commands\RequestMakeCommand;
use Laranex\BetterLaravel\Commands\RouteMakeCommand;

class BetterLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/better-laravel.php', 'better-laravel');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'better-laravel');

        if ($this->shouldRegisterRoutes()) {
            $this->registerRoutes();
        }

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            dirname(__DIR__).'/config/better-laravel.php' => config_path('better-laravel.php'),
        ], ['better-laravel', 'better-laravel-config']);

        $this->publishes([
            dirname(__DIR__).'/resources/views' => resource_path('views/vendor/better-laravel'),
        ], ['better-laravel', 'better-laravel-views']);

        $this->publishes([
            dirname(__DIR__).'/resources/stubs' => resource_path('stubs/vendor/better-laravel'),
        ], ['better-laravel', 'better-laravel-stubs']);

        $this->commands([
            RouteMakeCommand::class,
            ControllerMakeCommand::class,
            RequestMakeCommand::class,
            FeatureMakeCommand::class,
            OperationMakeCommand::class,
            JobMakeCommand::class,
        ]);
    }

    /**
     * Register every route file found under routes/web and routes/api.
     */
    public function registerRoutes(): void
    {
        $webRoutesPrefix = $this->config('web_routes_prefix', '');
        $apiRoutesPrefix = $this->config('api_routes_prefix', 'api');

        foreach (BetterLaravel::getAllFilesOfADirectory(base_path('routes/web'), 'php') as $route) {
            Route::middleware('web')
                ->prefix(is_string($webRoutesPrefix) ? $webRoutesPrefix : '')
                ->group($route);
        }

        foreach (BetterLaravel::getAllFilesOfADirectory(base_path('routes/api'), 'php') as $route) {
            Route::middleware('api')
                ->prefix(is_string($apiRoutesPrefix) ? $apiRoutesPrefix : '')
                ->group($route);
        }
    }

    /**
     * Routes are only registered when enabled and not already cached.
     */
    protected function shouldRegisterRoutes(): bool
    {
        if (! (bool) $this->config('enable_routes', true)) {
            return false;
        }

        return ! ($this->app instanceof CachesRoutes && $this->app->routesAreCached());
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->app->make(ConfigRepository::class)->get('better-laravel.'.$key, $default);
    }
}
