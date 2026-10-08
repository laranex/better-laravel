<?php

declare(strict_types=1);
use Pest\Concerns\Testable;

// Pest 3+ ships architecture presets; Pest 2 (the PHP 8.1 lane) does not.
if (method_exists(Testable::class, 'preset')) {
    arch()->preset()->php();

    arch()->preset()->security();
}

arch('it will not use dd(), ddd(), dump(), env(), or exit()')
    ->expect(['dd', 'ddd', 'dump', 'env', 'exit'])
    ->each->not->toBeUsed();

arch('the package source declares strict types')
    ->expect('Laranex\BetterLaravel')
    ->toUseStrictTypes();

arch('commands extend the base command')
    ->expect('Laranex\BetterLaravel\Commands')
    ->classes()
    ->toExtend('Laranex\BetterLaravel\Commands\BaseCommand')
    ->ignoring('Laranex\BetterLaravel\Commands\BaseCommand');

arch('generators extend the base generator')
    ->expect('Laranex\BetterLaravel\Generators')
    ->classes()
    ->toExtend('Laranex\BetterLaravel\Generators\Generator')
    ->ignoring('Laranex\BetterLaravel\Generators\Generator');
