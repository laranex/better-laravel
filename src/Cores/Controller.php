<?php

namespace Laranex\BetterLaravel\Cores;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Laranex\BetterLaravel\Bus\ServesFeature;

class Controller
{
    use ValidatesRequests, ServesFeature;
}
