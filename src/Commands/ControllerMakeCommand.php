<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\ControllerGenerator;

class ControllerMakeCommand extends BaseCommand
{
    public $signature = 'better:controller
                        {controller : Controller}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    public $description = 'Create a new controller in a module';

    public function handle(): int
    {
        $feature = $this->argument('controller');
        $module = $this->argument('module');
        $force = $this->option('force');

        $output = (new ControllerGenerator())->generate($feature, $module, $force);

        $this->printDecoratedOutput($output);

        return 0;
    }
}
