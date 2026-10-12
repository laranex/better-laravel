<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;
use Laranex\BetterLaravel\Generators\RouteGenerator;

it('rejects names that do not produce a valid PHP identifier', function (string $command, array $arguments, string $type, string $name, string $identifier) {
    $this->artisan($command, $arguments)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput("The $type name [$name] is invalid: [$identifier] is not a valid PHP identifier. Use only letters, numbers and underscores, starting with a letter or an underscore."))
        ->assertExitCode(1);
})->with([
    'feature' => ['better:feature', ['feature' => 'A;system(1)', 'module' => 'Blog'], 'feature', 'A;system(1)', 'A;system(1)Feature'],
    'feature module' => ['better:feature', ['feature' => 'createPost', 'module' => '..'], 'module', '..', '..Module'],
    'controller' => ['better:controller', ['controller' => 'Post.php', 'module' => 'Blog'], 'controller', 'Post.php', 'Post.phpController'],
    'controller module' => ['better:controller', ['controller' => 'Post', 'module' => '1st'], 'module', '1st', '1stModule'],
    'operation' => ['better:operation', ['operation' => "x'];", 'module' => 'Blog'], 'operation', "x'];", "X'];Operation"],
    'operation module' => ['better:operation', ['operation' => 'slugify', 'module' => 'Blog;'], 'module', 'Blog;', 'Blog;Module'],
    'job' => ['better:job', ['job' => 'A;system(1)', 'domain' => 'Blog'], 'job', 'A;system(1)', 'A;system(1)Job'],
    'job domain' => ['better:job', ['job' => 'send', 'domain' => '..'], 'domain', '..', '..'],
    'request' => ['better:request', ['request' => 'Store-Post!', 'domain' => 'Blog'], 'request', 'Store-Post!', 'StorePost!Request'],
    'request domain' => ['better:request', ['request' => 'StorePost', 'domain' => '..'], 'domain', '..', '..'],
]);

it('rejects route names that would break out of the generated route file', function (string $route, string $generated) {
    $this->artisan('better:route', ['route' => $route])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput("The route name [$route] is invalid: [$generated] may only contain lowercase letters, numbers, \"-\" and \"_\"."))
        ->assertExitCode(1);
})->with([
    'quote' => ["x'];", "x'];"],
    'dots' => ['..', '..'],
    'extension' => ['post.php', 'post.phps'],
]);

it('rejects a versionOrDirectory that is not a plain relative path', function (string $directory) {
    $this->artisan('better:route', ['route' => 'post', 'versionOrDirectory' => $directory])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput("The versionOrDirectory [$directory] is invalid: use \"/\"-separated segments of lowercase letters, numbers, \"-\" and \"_\" only."))
        ->assertExitCode(1);

    expect(base_path('posts.php'))->not->toBeFile()
        ->and(base_path('routes/posts.php'))->not->toBeFile();
})->with(['..', '../..', 'v1/../..', 'v1\\..', 'admin\\v1', 'v1//admin', 'v1.0', "v1'"]);

it('accepts nested route directories made of plain segments', function () {
    $this->cleanUp(base_path('routes/api/admin'));

    $this->artisan('better:route', ['route' => 'user', 'versionOrDirectory' => '/Admin/v1_beta-2/', '--api' => true])->assertExitCode(0);

    expect(file_get_contents(base_path('routes/api/admin/v1_beta-2/users.php')))->toContain("'prefix' => 'admin/v1_beta-2/users'");
});

it('only generates web or api route files', function () {
    expect(fn () => (new RouteGenerator)->generate('post', '', '../../app'))
        ->toThrow(InvalidArgumentException::class, 'The route file type [../../app] is invalid: use "web" or "api".');

    expect(base_path('app/posts.php'))->not->toBeFile();
});
