<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\FeatureGenerator;

class FeatureMakeCommand extends BaseCommand
{
    public $signature = 'better:feature
                        {feature : Feature}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    public $description = 'Create a new feature in a module';

    public function handle(): int
    {
        $feature = $this->argument('feature');
        $module = $this->argument('module');
        $force = $this->option('force');

        $output = (new FeatureGenerator())->generate($feature, $module, $force);

        $this->printDecoratedOutput($output);

        return 0;
    }
}
