<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Foundation\Bus\DispatchesJobs;

trait ServesFeature
{
    use DispatchesJobs, Dispatcher;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param  string  $feature
     */
    public function serve(mixed $feature, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($feature, $arguments));
    }
}
