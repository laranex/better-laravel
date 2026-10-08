<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\OperationGenerator;

class OperationMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better:operation
                        {operation : Operation}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new operation in a module';

    /**
     * Execute the console command.
     */
    public function handle(OperationGenerator $generator): int
    {
        return $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('operation'),
            $this->stringArgument('module'),
            (bool) $this->option('force'),
        ));
    }
}
