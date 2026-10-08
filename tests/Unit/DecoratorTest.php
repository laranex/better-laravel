<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('turns an absolute path into a path relative to the application', function () {
    expect(Decorator::getRelativePath(base_path('app/Modules/BlogModule/Features/StoreBlogFeature.php')))
        ->toBe('app/Modules/BlogModule/Features/StoreBlogFeature.php')
        ->and(Decorator::getRelativePath('/somewhere/else.php'))->toBe('somewhere/else.php');
});

it('decorates the generated file message with the relative path', function () {
    expect(Decorator::getFileGeneratedOutput(base_path('routes/web/blogs.php')))
        ->toBe('🚀🚀🚀 [routes/web/blogs.php has been successfully generated!] 🚀🚀🚀');
});

it('decorates error messages', function () {
    expect(Decorator::getFileGenerationErrorOutput('app/Foo.php already exists!'))
        ->toBe('🚀🚀🚀 [app/Foo.php already exists!] 🚀🚀🚀');
});

it('names the better-laravel config key in the disabled routes warning', function () {
    expect(Decorator::getDisableRoutesWarning())
        ->toContain('better-laravel.enable_routes')
        ->toStartWith('🚀🚀🚀 [')
        ->toEndWith('] 🚀🚀🚀');
});
