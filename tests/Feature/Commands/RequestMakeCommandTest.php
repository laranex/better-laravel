<?php

declare(strict_types=1);

use Laranex\BetterLaravel\Decorator;

it('generates a request inside a domain', function () {
    $this->cleanUp(app_path('Domains/Content'));
    $path = app_path('Domains/Content/Requests/StoreContentRequest.php');

    $this->artisan('better:request', ['request' => 'storeContent', 'domain' => 'content'])
        ->expectsOutput(Decorator::getFileGeneratedOutput($path))
        ->assertExitCode(0);

    expect(file_get_contents($path))
        ->toContain('namespace App\Domains\Content\Requests;')
        ->toContain('use Laranex\BetterLaravel\Cores\Request;')
        ->toContain('class StoreContentRequest extends Request')
        ->toContain('public function rules(): array');
});

it('refuses to overwrite an existing request unless forced', function () {
    $this->cleanUp(app_path('Domains/Auth'));

    $this->artisan('better:request', ['request' => 'Login', 'domain' => 'Auth'])->assertExitCode(0);

    $this->artisan('better:request', ['request' => 'LoginRequest', 'domain' => 'auth'])
        ->expectsOutput(Decorator::getFileGenerationErrorOutput('app/Domains/Auth/Requests/LoginRequest.php already exists!'))
        ->assertExitCode(1);

    $this->artisan('better:request', ['request' => 'Login', 'domain' => 'Auth', '--force' => true])->assertExitCode(0);
});

it('prefers stubs published to resources/stubs/vendor/better-laravel', function () {
    $this->cleanUp(app_path('Domains/Custom'));
    $stubs = $this->cleanUp(resource_path('stubs/vendor/better-laravel'));

    mkdir($stubs, 0755, true);
    file_put_contents("$stubs/request.php.stub", "<?php\n\nnamespace {{namespace}};\n\n// custom stub\nclass {{request}} {}\n");

    $this->artisan('better:request', ['request' => 'Custom', 'domain' => 'Custom'])->assertExitCode(0);

    expect(file_get_contents(app_path('Domains/Custom/Requests/CustomRequest.php')))
        ->toContain('// custom stub')
        ->toContain('namespace App\Domains\Custom\Requests;')
        ->toContain('class CustomRequest {}');
});
