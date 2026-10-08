<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('generates a feature inside a module', function () {
    $this->cleanUp(app_path('Modules/BlogModule'));
    $path = app_path('Modules/BlogModule/Features/StoreBlogFeature.php');

    $this->artisan('better:feature', ['feature' => 'StoreBlog', 'module' => 'Blog'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('namespace App\Modules\BlogModule\Features;')
        ->toContain('use Laranex\BetterLaravel\Cores\Feature;')
        ->toContain('class StoreBlogFeature extends Feature')
        ->toContain('public function handle(Request $request)');
});

it('refuses to overwrite an existing feature unless forced', function () {
    $this->cleanUp(app_path('Modules/NewsModule'));
    $path = app_path('Modules/NewsModule/Features/PublishNewsFeature.php');

    $this->artisan('better:feature', ['feature' => 'publishNews', 'module' => 'news'])->assertExitCode(0);

    $this->artisan('better:feature', ['feature' => 'PublishNewsFeature.php', 'module' => 'NewsModule'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('app/Modules/NewsModule/Features/PublishNewsFeature.php already exists!'))
        ->assertExitCode(1);

    $this->artisan('better:feature', ['feature' => 'publishNews', 'module' => 'news', '--force' => true])->assertExitCode(0);

    expect(file_exists($path))->toBeTrue();
});
