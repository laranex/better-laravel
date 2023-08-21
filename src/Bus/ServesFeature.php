<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Support\Facades\Bus;
use Laranex\BetterLaravel\BetterLaravel;

trait ServesFeature
{
    public function serve(mixed $feature, array $arguments = []): mixed
    {
        return Bus::dispatchNow(BetterLaravel::getDispatchableUnit($feature, $arguments));
    }
}
