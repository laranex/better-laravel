<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Bus\PendingDispatch;
use Laranex\BetterLaravel\Cores\Job;
use Laranex\BetterLaravel\Cores\Operation;
use Laranex\BetterLaravel\Cores\QueueableJob;

/**
 * Dispatch units (jobs and operations) synchronously or onto a queue.
 */
trait UnitDispatcher
{
    use DispatchesJobs;

    /**
     * Run the given unit immediately and return whatever its handle method returns.
     */
    public function run(Job|Operation $unit): mixed
    {
        return $this->dispatchSync($unit);
    }

    /**
     * Push the given job onto a queue for asynchronous execution.
     */
    public function runInQueue(QueueableJob $unit, string $queue = 'default'): PendingDispatch
    {
        $unit->onQueue($queue);

        return $this->dispatch($unit);
    }
}
