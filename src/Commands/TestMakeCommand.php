<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\TestGenerator;

class TestMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'better:test
                        {test : Test}
                        {domain : Domain}
                        {--J|job : Create a unit test for job}
                        {--D|dry : Dry run the command}
                        {--F|force : Overwrite existing files}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new test in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $test = $this->argument('test');
            $domain = $this->argument('domain');
            $job = $this->option('job');
            $dry = $this->option('dry');
            $force = $this->option('force');

            $output = (new TestGenerator)->generate($test, $domain, $job, $force, $dry);
            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
