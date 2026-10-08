<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<int, string> getAllFilesOfADirectory(string $directory, string $extension = '')
 *
 * @see \Laranex\BetterLaravel\BetterLaravel
 */
class BetterLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laranex\BetterLaravel\BetterLaravel::class;
    }
}
