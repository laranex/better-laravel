<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\JobGenerator;
use Laranex\BetterLaravel\Generators\TestGenerator;

class JobMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'better:job
                        {job : Job}
                        {domain : Domain}
                        {--Q|queue : Make the job queueable}
                        {--F|force : Overwrite existing files}
                        {--D|dry : Dry run the command}
                        {--T|test : Generate a unit test for the job}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new job in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $job = $this->argument('job');
            $domain = $this->argument('domain');
            $queueable = $this->option('queue');
            $force = $this->option('force');
            $dry = $this->option('dry');
            $test = $this->option('test');

            $output = (new JobGenerator)->generate($job, $domain, $queueable, $force, $dry);

            $this->printFileGeneratedOutput($output);

            if ($test) {
                $testOutput = (new TestGenerator)->generate($job, $domain, true, $force, $dry);
                $this->printFileGeneratedOutput($testOutput);
            }
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
