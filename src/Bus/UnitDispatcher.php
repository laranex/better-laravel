<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Foundation\Bus\Dispatchable;
use InvalidArgumentException;
use Illuminate\Support\Facades\Bus;

trait UnitDispatcher
{
    /**
     * @param mixed $unit
     * @param array $arguments
     * @return mixed
     */
    public function run(mixed $unit, array $arguments = []): mixed
    {
        return Bus::dispatchNow($this->getDispatchableUnit($unit, $arguments));
    }

    /**
     * @param mixed $unit
     * @param array $arguments
     * @param string $queue
     *
     * @return mixed
     */
    public function runInQueue(mixed $unit, array $arguments = [], string $queue = "default"): mixed
    {
        $dispatchableUnit = $this->getDispatchableUnit($unit, $arguments);
        $dispatchableUnit->onQueue($queue);

        return Bus::dispatchToQueue($dispatchableUnit);
    }


    /**
     * @param mixed $unit
     * @param array $arguments
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    private function getDispatchableUnit(mixed $unit, array $arguments): mixed
    {
        $dispatchableUnit = is_string($unit) ? new $unit(...$arguments) : $unit;

        if (!in_array(Dispatchable::class, class_uses($dispatchableUnit))) {
            throw new InvalidArgumentException("The given unit is not Dispatchable");
        }

        return $dispatchableUnit;
    }
}
