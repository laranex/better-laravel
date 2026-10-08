<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('generates a controller inside a module', function () {
    $this->cleanUp(app_path('Modules/ShopModule'));
    $path = app_path('Modules/ShopModule/Http/Controllers/ProductController.php');

    $this->artisan('better:controller', ['controller' => 'product', 'module' => 'shop'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('namespace App\Modules\ShopModule\Http\Controllers;')
        ->toContain('use Laranex\BetterLaravel\Cores\Controller;')
        ->toContain('class ProductController extends Controller');
});

it('refuses to overwrite an existing controller unless forced', function () {
    $this->cleanUp(app_path('Modules/CatalogModule'));
    $path = app_path('Modules/CatalogModule/Http/Controllers/ItemController.php');

    $this->artisan('better:controller', ['controller' => 'Item', 'module' => 'CatalogModule'])->assertExitCode(0);
    file_put_contents($path, '<?php // edited');

    $this->artisan('better:controller', ['controller' => 'Item', 'module' => 'Catalog'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('app/Modules/CatalogModule/Http/Controllers/ItemController.php already exists!'))
        ->assertExitCode(1);

    expect(file_get_contents($path))->toBe('<?php // edited');

    $this->artisan('better:controller', ['controller' => 'Item', 'module' => 'Catalog', '--force' => true])->assertExitCode(0);

    expect(file_get_contents($path))->toContain('class ItemController extends Controller');
});
