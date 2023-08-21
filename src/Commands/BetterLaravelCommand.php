<?php

namespace Laranex\BetterLaravel\Commands;

use Illuminate\Console\Command;

class BetterLaravelCommand extends Command
{
    public $signature = 'better-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
