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
 *
 */
trait UnitDispatcher
{
    use Dispatcher, DispatchesJobs;

    /**
     * Dispatch the given unit synchronously with the provided arguments.
     *
     * This method will run the unit immediately in the current process and return the result.
     * The unit can be either a class name (string) or an instance of a Job/Operation.
     *
     * @param  string|Job|Operation  $unit  The unit to dispatch (class name or instance)
     * @param  array<string, mixed>  $arguments  Arguments to pass to the unit's constructor
     * @return mixed  The result returned by the unit's execution
     *
     * @deprecated Passing a string class name to run method is deprecated and will be removed in a future version. Please instantiate the unit directly: use `new YourJob()` instead of `YourJob::class`.
     */
    public function run(mixed $unit, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($unit, $arguments));
    }

    /**
     * Dispatch the given unit to a queue with the provided arguments.
     *
     * This method will queue the unit for asynchronous execution. The unit must be queueable
     * (extend QueueableJob). Operations are not yet supported for queueing and will throw an error.
     *
     * @param  string|Job|Operation  $unit  The unit to dispatch (class name or instance, must be queueable)
     * @param  array<string, mixed>  $arguments  Arguments to pass to the unit's constructor
     * @param  string  $queue  The queue name to dispatch the unit to (defaults to 'default')
     * @return mixed  The result of the dispatch operation
     *
     * @throws Error  If the unit does not support queues (must extend QueueableJob)
     * @throws Error  If the unit is an Operation (not yet supported for queueing)
     *
     * @deprecated Passing a string class name to runInQueue method is deprecated and will be removed in a future version. Please instantiate the unit directly: use `new YourJob()` instead of `YourJob::class`.
     */
    public function runInQueue(string|Job|Operation $unit, array $arguments = [], string $queue = 'default'): mixed
    {
        $dispatchableUnit = $this->getDispatchableUnit($unit, $arguments);

        try {
            $dispatchableUnit->onQueue($queue);
        } catch (Error $_) {

            /**
             * TODO remove the following condition once we provide QueueableOperation.
             * We put this here, instead of the very first line of this method since we dont want to effect the application performance
             * on normal queueable jobs by always checking a condition.
             */
            if ($dispatchableUnit instanceof Operation) {
                $packageName = json_decode(file_get_contents(dirname(__DIR__, 2).'/composer.json', true))?->name;
                throw new Error('['.$dispatchableUnit::class."is an Operation and is not allowed to be queue yet, $packageName will be providing it soon ]");
            }

            throw new Error('['.$dispatchableUnit::class.' does not support queues. Please extends to ['.QueueableJob::class.']');
        }

        return $this->dispatch($dispatchableUnit);
    }
}
