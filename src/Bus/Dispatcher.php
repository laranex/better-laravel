<?php

namespace Laranex\BetterLaravel\Bus;

use Laranex\BetterLaravel\Cores\Feature;
use Laranex\BetterLaravel\Cores\Job;
use Laranex\BetterLaravel\Cores\Operation;

/**
 * Trait Dispatcher
 *
 * Provides utility methods for dispatching units (jobs, operations, features).
 * This trait handles the instantiation of dispatchable units from class names or objects.
 *
 */
trait Dispatcher
{
    /**
     * Get the dispatchable unit.
     *
     * Resolves a dispatchable unit by either instantiating it from a class name
     * or returning the already instantiated object.
     *
     * @param string|Feature|Job|Operation $unit  The unit to dispatch - either a class name string or an object instance
     * @param array $arguments  The arguments to pass to the unit's constructor if it's a class name
     * @return Feature|Job|Operation  The instantiated unit ready for dispatch
     *
     * @deprecated Passing a string class name to dispatch methods is deprecated and will be removed in a future version.
     *             Please instantiate the unit directly: use `new YourUnit()` instead of `YourUnit::class`.
     */
    public function getDispatchableUnit(string|Feature|Job|Operation $unit, array $arguments): mixed
    {
        if (is_string($unit)) {
            trigger_error(
                'Passing a string class name to dispatch methods is deprecated and will be removed in a future version. ' .
                "Please instantiate the unit directly: use 'new YourUnit()' instead of 'YourUnit::class'.",
                E_USER_DEPRECATED
            );

            return new $unit(...$arguments);
        }

        return $unit;
    }
}
