<?php

namespace Laranex\BetterLaravel\Bus;

use Error;
use Illuminate\Foundation\Bus\DispatchesJobs;

trait UnitDispatcher
{
    use DispatchesJobs, Dispatcher;

    /**
     * Dispatch the given unit with the given arguments.
     *
     * @param  string  $unit
     */
    public function run(mixed $unit, array $arguments = []): mixed
    {
        return $this->dispatchSync($this->getDispatchableUnit($unit, $arguments));
    }

    /**
     * Serve the given unit with arguments in given queue.
     *
     * @param  string  $unit
     *
     * @throws Error
     */
    public function runInQueue(mixed $unit, array $arguments = [], string $queue = 'default'): mixed
    {
        $dispatchableUnit = $this->getDispatchableUnit($unit, $arguments);
        try {
            $dispatchableUnit->onQueue($queue);
        } catch (Error $_) {
            throw new Error('['.$dispatchableUnit::class." does not support queues. Please extends to [Laranex\BetterLaravel\Cores\QueueableJob]");
        }

        return $this->dispatch($dispatchableUnit);
    }
}
