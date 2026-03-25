<?php

namespace Laranex\BetterLaravel\Bus;

use Error;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Laranex\BetterLaravel\Cores\Job;
use Laranex\BetterLaravel\Cores\Operation;
use Laranex\BetterLaravel\Cores\QueueableJob;

/**
 * Trait UnitDispatcher
 *
 * Provides functionality to dispatch units (Jobs, Operations) synchronously or to queues.
 * This trait combines the Dispatcher and DispatchesJobs traits to offer a unified interface
 * for running units with or without queue support.
 */
trait UnitDispatcher
{
    use DispatchesJobs;

    /**
     * Dispatch the given unit synchronously with the provided arguments.
     *
     * This method will run the unit immediately in the current process and return the result.
     *
     *
     * @param  Job|Operation  $unit  The unit to dispatch (must be an instance)
     * @return mixed The result returned by the unit's execution
     */
    public function run(Job|Operation $unit): mixed
    {
        return $this->dispatchSync($unit);
    }

    /**
     * Dispatch the given unit to a queue for asynchronous execution.
     *
     * This method will queue the unit for asynchronous execution. The unit must be queueable
     * (extend QueueableJob). Operations are not yet supported for queueing and will throw an error.
     *
     *
     * @param  QueueableJob  $unit  The unit to dispatch (must be an instance)
     * @param  string  $queue  The queue name to dispatch the unit to (defaults to 'default')
     * @return mixed The result of the dispatch operation
     */
    public function runInQueue(QueueableJob $unit, string $queue = 'default'): mixed
    {
        try {
            $unit->onQueue($queue);
        } catch (Error $_) {
            throw new Error('['.$unit::class.' does not support queues. Please extends to ['.QueueableJob::class.']');
        }

        return $this->dispatch($unit);
    }
}
