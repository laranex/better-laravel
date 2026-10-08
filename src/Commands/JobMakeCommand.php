<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\JobGenerator;

class JobMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better:job
                        {job : Job}
                        {domain : Domain}
                        {--Q|queue : Make the job queueable}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job in a domain';

    /**
     * Execute the console command.
     */
    public function handle(JobGenerator $generator): int
    {
        return $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('job'),
            $this->stringArgument('domain'),
            (bool) $this->option('queue'),
            (bool) $this->option('force'),
        ));
    }
}
