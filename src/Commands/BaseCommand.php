<?php

namespace Laranex\BetterLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Laranex\BetterLaravel\Decorator;

class BaseCommand extends Command
{
    public function printDecoratedOutput($output)
    {
        $this->info(Decorator::getGeneratedOutput($output));
        $this->comment(Inspiring::quote());
    }
}
