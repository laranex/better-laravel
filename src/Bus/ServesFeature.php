<?php

namespace Laranex\BetterLaravel\Bus;

use Illuminate\Support\Facades\Bus;
use Laranex\BetterLaravel\BetterLaravel;

trait ServesFeature
{
    /**
     * @param  mixed  $unit
     * @return mixed
     */
    public function serve($feature, array $arguments = [])
    {
        return Bus::dispatchNow(BetterLaravel::getDispatchableUnit($feature, $arguments));
    }
}
