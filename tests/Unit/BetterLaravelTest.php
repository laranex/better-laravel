<?php

declare(strict_types=1);

use Laranex\BetterLaravel\BetterLaravel;
use Laranex\BetterLaravel\Facades\BetterLaravel as BetterLaravelFacade;

it('returns an empty list for a missing directory', function () {
    expect(BetterLaravel::getAllFilesOfADirectory(base_path('routes/does-not-exist')))->toBe([]);
});

it('lists files recursively, sorted, optionally filtered by extension', function () {
    $directory = $this->cleanUp(storage_path('better-laravel-files-'.uniqid()));

    mkdir("$directory/nested/deeper", 0755, true);
    file_put_contents("$directory/b.php", '<?php');
    file_put_contents("$directory/a.txt", 'text');
    file_put_contents("$directory/nested/c.php", '<?php');
    file_put_contents("$directory/nested/deeper/d.php", '<?php');

    // The iterator joins the entries it finds with the native directory separator.
    $path = fn (string ...$parts): string => implode(DIRECTORY_SEPARATOR, [$directory, ...$parts]);

    expect(BetterLaravel::getAllFilesOfADirectory($directory, 'php'))->toBe([
        $path('b.php'),
        $path('nested', 'c.php'),
        $path('nested', 'deeper', 'd.php'),
    ])->and(BetterLaravel::getAllFilesOfADirectory($directory))->toHaveCount(4)
        ->and(BetterLaravel::getAllFilesOfADirectory($directory, 'txt'))->toBe([$path('a.txt')]);
});

it('is reachable through the facade', function () {
    $fixtures = dirname(__DIR__).'/Fixtures/routes/web';

    expect(BetterLaravelFacade::getFacadeRoot())->toBeInstanceOf(BetterLaravel::class)
        ->and(BetterLaravelFacade::getAllFilesOfADirectory($fixtures, 'php'))->toBe([$fixtures.DIRECTORY_SEPARATOR.'blogs.php']);
});
