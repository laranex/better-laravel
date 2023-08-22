<?php

namespace Laranex\BetterLaravel\Cores;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueableJob implements ShouldQueue
{
    use SerializesModels;
    use InteractsWithQueue;
    use Queueable;
}
