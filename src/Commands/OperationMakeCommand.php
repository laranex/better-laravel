<?php

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\OperationGenerator;

class OperationMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'better:operation
                        {operation : Operation}
                        {domain : Domain}
                        {--F|force : Overwrite existing files}
                        {--D|dry : Dry run the command}';

    /**
     * The description the console command.
     *
     * @var string
     */
    public $description = 'Create a new operation in a domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $operation = $this->argument('operation');
            $domain = $this->argument('domain');
            $force = $this->option('force');
            $dry = $this->option('dry');

            $output = (new OperationGenerator)->generate($operation, $domain, $force, $dry);

            $this->printFileGeneratedOutput($output);
        } catch (\Exception $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());
        }

        return 0;
    }
}
