<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\FeatureGenerator;
use Laranex\BetterLaravel\Generators\TestGenerator;

class FeatureMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'better:feature
                        {feature : Feature}
                        {module : Module}
                        {--F|force : Overwrite existing files}
                        {--D|dry : Dry run the command}
                        {--T|test : Generate a feature test}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new feature in a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $feature = $this->argument('feature');
            $module = $this->argument('module');
            $force = $this->option('force');
            $dry = $this->option('dry');
            $test = $this->option('test');

            $output = (new FeatureGenerator)->generate($feature, $module, $force, $dry);

            $this->printFileGeneratedOutput($output);

            if ($test) {
                $testOutput = (new TestGenerator)->generate($feature, $module, false, $force, $dry);
                $this->printFileGeneratedOutput($testOutput);
            }
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
