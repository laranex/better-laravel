<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('rejects names containing a path separator', function (string $command, array $arguments, string $type, string $name) {
    $this->artisan($command, $arguments)
        ->expectsOutput(Decorator::getFileGenerationErrorOutput("The $type name [$name] must not contain \"/\" or \"\\\". Nested names are not supported."))
        ->assertExitCode(1);
})->with([
    'feature' => ['better:feature', ['feature' => 'Blog/createPost', 'module' => 'Blog'], 'feature', 'Blog/createPost'],
    'feature with a backslash' => ['better:feature', ['feature' => 'Blog\\CreatePost', 'module' => 'Blog'], 'feature', 'Blog\\CreatePost'],
    'feature module' => ['better:feature', ['feature' => 'createPost', 'module' => 'Admin/Blog'], 'module', 'Admin/Blog'],
    'controller' => ['better:controller', ['controller' => 'Admin/Post', 'module' => 'Blog'], 'controller', 'Admin/Post'],
    'controller module' => ['better:controller', ['controller' => 'Post', 'module' => 'Admin\\Blog'], 'module', 'Admin\\Blog'],
    'operation' => ['better:operation', ['operation' => 'Blog/slugify', 'module' => 'Blog'], 'operation', 'Blog/slugify'],
    'operation module' => ['better:operation', ['operation' => 'slugify', 'module' => 'Admin/Blog'], 'module', 'Admin/Blog'],
    'job' => ['better:job', ['job' => 'Mail/send', 'domain' => 'Blog'], 'job', 'Mail/send'],
    'job domain' => ['better:job', ['job' => 'send', 'domain' => 'Admin/Blog'], 'domain', 'Admin/Blog'],
    'request' => ['better:request', ['request' => 'Admin/StorePost', 'domain' => 'Blog'], 'request', 'Admin/StorePost'],
    'request domain' => ['better:request', ['request' => 'StorePost', 'domain' => 'Admin\\Blog'], 'domain', 'Admin\\Blog'],
]);
