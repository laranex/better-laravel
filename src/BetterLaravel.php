<?php

namespace Laranex\BetterLaravel;

use Illuminate\Foundation\Bus\Dispatchable;
use InvalidArgumentException;

class BetterLaravel
{
    /**
     * @throws InvalidArgumentException
     */
    public static function getDispatchableUnit(mixed $unit, array $arguments): mixed
    {
        $dispatchableUnit = is_string($unit) ? new $unit(...$arguments) : $unit;

        if (! in_array(Dispatchable::class, class_uses_recursive($dispatchableUnit))) {
            throw new InvalidArgumentException('The given unit is not Dispatchable');
        }

        return $dispatchableUnit;
    }
}
