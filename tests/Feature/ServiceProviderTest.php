<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laranex\BetterLaravel\BetterLaravelServiceProvider;

it('merges the default configuration', function () {
    expect(config('better-laravel'))->toBe([
        'enable_routes' => true,
        'web_routes_prefix' => '',
        'api_routes_prefix' => 'api',
    ]);
});

it('registers every better:* command', function () {
    expect(Artisan::all())->toHaveKeys([
        'better:route',
        'better:controller',
        'better:request',
        'better:feature',
        'better:operation',
        'better:job',
    ]);
});

it('registers the better-laravel view namespace', function () {
    expect(View::exists('better-laravel::welcome'))->toBeTrue()
        ->and(view('better-laravel::welcome')->render())->toContain('<title>Better Laravel</title>');
});

it('publishes the config, views and stubs under their own tags', function () {
    $package = dirname(__DIR__, 2);

    expect(ServiceProvider::pathsToPublish(BetterLaravelServiceProvider::class, 'better-laravel-config'))
        ->toBe(["$package/config/better-laravel.php" => config_path('better-laravel.php')])
        ->and(ServiceProvider::pathsToPublish(BetterLaravelServiceProvider::class, 'better-laravel-views'))
        ->toBe(["$package/resources/views" => resource_path('views/vendor/better-laravel')])
        ->and(ServiceProvider::pathsToPublish(BetterLaravelServiceProvider::class, 'better-laravel-stubs'))
        ->toBe(["$package/resources/stubs" => resource_path('stubs/vendor/better-laravel')])
        ->and(ServiceProvider::pathsToPublish(BetterLaravelServiceProvider::class, 'better-laravel'))
        ->toHaveCount(3);
});

it('loads route files from routes/web with the web middleware group', function () {
    $route = Route::getRoutes()->getByName('better-laravel-fixture.blogs');

    expect($route)->not->toBeNull()
        ->and($route?->uri())->toBe('blogs')
        ->and($route?->gatherMiddleware())->toBe(['web']);

    $this->get('/blogs')->assertOk()->assertSee('blogs');
});

it('loads route files from nested routes/api directories with the api prefix and middleware group', function () {
    $route = Route::getRoutes()->getByName('better-laravel-fixture.posts');

    expect($route)->not->toBeNull()
        ->and($route?->uri())->toBe('api/v1/posts')
        ->and($route?->gatherMiddleware())->toBe(['api']);

    $this->getJson('/api/v1/posts')->assertOk();
});

/**
 * @return array<int, string>
 */
function registeredUris(): array
{
    return array_map(fn ($route): string => $route->uri(), Route::getRoutes()->getRoutes());
}

it('applies the configured route prefixes', function () {
    config()->set('better-laravel.web_routes_prefix', 'site');
    config()->set('better-laravel.api_routes_prefix', 'rest');

    app(BetterLaravelServiceProvider::class, ['app' => app()])->registerRoutes();

    expect(registeredUris())->toContain('site/blogs', 'rest/v1/posts');
});

it('does not load route files when enable_routes is disabled', function () {
    config()->set('better-laravel.enable_routes', false);
    config()->set('better-laravel.web_routes_prefix', 'disabled-web');
    config()->set('better-laravel.api_routes_prefix', 'disabled-api');

    app(BetterLaravelServiceProvider::class, ['app' => app()])->boot();

    expect(registeredUris())->not->toContain('disabled-web/blogs', 'disabled-api/v1/posts');
});

it('loads route files when the provider boots with routes enabled', function () {
    config()->set('better-laravel.web_routes_prefix', 'enabled-web');
    config()->set('better-laravel.api_routes_prefix', 'enabled-api');

    app(BetterLaravelServiceProvider::class, ['app' => app()])->boot();

    expect(registeredUris())->toContain('enabled-web/blogs', 'enabled-api/v1/posts');
});
