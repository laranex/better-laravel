<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Laranex\BetterLaravel\Cores\Feature;

/**
 * Trait ServesFeature
 *
 * Provides functionality to serve features synchronously.
 * This trait combines the Dispatcher and DispatchesJobs traits to enable
 * synchronous execution of feature classes within the Better Laravel architecture.
 */
trait ServesFeature
{
    use Dispatcher, DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * Dispatches a feature synchronously and returns its result. The feature can be
     * provided as either a class name string or an instantiated object. Arguments
     * are passed to the feature's constructor if a class name is provided.
     *
     * @param  string|Feature  $feature  The feature to serve - either a fully qualified class name or an instance
     * @param  array  $arguments  The arguments to pass to the feature's constructor (only used if $feature is a class name)
     * @return mixed The result returned by the feature's execution
     *
     * @deprecated Passing a string class name to serve method is deprecated and will be removed in a future version. Please instantiate the unit directly: use `new YourFeature()` instead of `YourFeature::class`.
     */
    public function serve(string|Feature $feature, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($feature, $arguments));
    }
}
