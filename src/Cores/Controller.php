<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Cores;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Laranex\BetterLaravel\Bus\ServesFeature;

class Controller
{
    use ServesFeature, ValidatesRequests;
}
