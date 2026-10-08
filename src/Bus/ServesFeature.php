<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Laranex\BetterLaravel\Cores\Feature;

/**
 * Serve features synchronously from a controller.
 */
trait ServesFeature
{
    use DispatchesJobs;

    /**
     * Serve the given feature and return whatever its handle method returns.
     */
    public function serve(Feature $feature): mixed
    {
        return $this->dispatchSync($feature);
    }
}
