<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Laranex\BetterLaravel\Decorator;

it('generates a web route file', function () {
    $path = $this->cleanUp(base_path('routes/web/articles.php'));

    $this->artisan('better:route', ['route' => 'article'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->doesntExpectOutput(Decorator::getDisableRoutesWarning())
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain("Route::group(['prefix' => 'articles']")
        ->toContain("view('better-laravel::welcome')");
});

it('generates a versioned api route file with the --api option', function () {
    $this->cleanUp(base_path('routes/api/v2'));
    $path = base_path('routes/api/v2/comments.php');

    $this->artisan('better:route', ['route' => 'Comment', 'versionOrDirectory' => 'V2', '--api' => true])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))->toContain("Route::group(['prefix' => 'v2/comments']");
});

it('refuses to overwrite an existing route file unless forced', function () {
    $this->cleanUp(base_path('routes/web/admin'));

    $this->artisan('better:route', ['route' => 'user', 'versionOrDirectory' => 'admin'])->assertExitCode(0);

    $this->artisan('better:route', ['route' => 'users', 'versionOrDirectory' => 'Admin'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('routes/web/admin/users.php already exists!'))
        ->assertExitCode(1);

    $this->artisan('better:route', ['route' => 'user', 'versionOrDirectory' => 'admin', '--force' => true])->assertExitCode(0);
});

it('warns when route loading is disabled', function () {
    config()->set('better-laravel.enable_routes', false);
    $path = $this->cleanUp(base_path('routes/web/tags.php'));

    $this->artisan('better:route', ['route' => 'tag'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->expectsOutput(Decorator::getDisableRoutesWarning())
        ->assertExitCode(0);
});

it('generates a route file that Laravel can load', function () {
    $path = $this->cleanUp(base_path('routes/web/galleries.php'));

    $this->artisan('better:route', ['route' => 'gallery'])->assertExitCode(0);

    Route::middleware('web')->group($path);

    $this->get('/galleries')->assertOk()->assertSee('<title>Better Laravel</title>', false);
});

it('rejects a nested route name', function (string $route) {
    $this->artisan('better:route', ['route' => $route])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput("The route name [$route] must not contain \"/\" or \"\\\". Nested names are not supported."))
        ->assertExitCode(1);

    expect(base_path('routes/web/admin/users.php'))->not->toBeFile();
})->with(['admin/users', 'admin\\users']);

it('keeps filling the legacy versionOrDirectory placeholder of previously published stubs', function () {
    $this->cleanUp(resource_path('stubs/vendor/better-laravel'));
    $this->cleanUp(base_path('routes/api/v3'));
    $stub = resource_path('stubs/vendor/better-laravel/route.php.stub');
    mkdir(dirname($stub), 0755, true);
    file_put_contents($stub, "<?php // '{{versionOrDirectory}}/{{route}}'");

    $this->artisan('better:route', ['route' => 'post', 'versionOrDirectory' => 'v3', '--api' => true])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/api/v3/posts.php')))->toBe("<?php // '/v3/posts'");
});
