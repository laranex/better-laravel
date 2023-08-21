<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Support\Facades\Bus;
use Laranex\BetterLaravel\BetterLaravel;

trait UnitDispatcher
{
    public function run(mixed $unit, array $arguments = []): mixed
    {
        return Bus::dispatchNow(BetterLaravel::getDispatchableUnit($unit, $arguments));
    }

    public function runInQueue(mixed $unit, array $arguments = [], string $queue = 'default'): mixed
    {
        $dispatchableUnit = BetterLaravel::getDispatchableUnit($unit, $arguments);
        $dispatchableUnit->onQueue($queue);

        return Bus::dispatchToQueue($dispatchableUnit);
    }
}
