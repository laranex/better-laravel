<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Cores;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueableJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
}
