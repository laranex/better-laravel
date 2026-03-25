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
    use DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * Dispatches a feature synchronously and returns its result. The feature can be
     * provided as either a class name string or an instantiated object. Arguments
     * are passed to the feature's constructor if a class name is provided.
     *
     * @param  Feature  $feature  The feature to serve - either a fully qualified class name or an instance
     * @return mixed The result returned by the feature's execution
     *
     */
    public function serve(Feature $feature): mixed
    {
        return $this->dispatchSync($feature);
    }
}
