<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\RequestGenerator;

class RequestMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better:request
                        {request : Request}
                        {domain : Domain}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request in a domain';

    /**
     * Execute the console command.
     */
    public function handle(RequestGenerator $generator): int
    {
        return $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('request'),
            $this->stringArgument('domain'),
            (bool) $this->option('force'),
        ));
    }
}
