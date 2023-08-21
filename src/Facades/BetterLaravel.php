<?php

namespace Laranex\BetterLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laranex\BetterLaravel\BetterLaravel
 */
class BetterLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Laranex\BetterLaravel\BetterLaravel::class;
    }
}
