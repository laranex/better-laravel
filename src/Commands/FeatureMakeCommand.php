<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Laranex\BetterLaravel\Generators\FeatureGenerator;

class FeatureMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better:feature
                        {feature : Feature}
                        {module : Module}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature in a module';

    /**
     * Execute the console command.
     */
    public function handle(FeatureGenerator $generator): int
    {
        return $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('feature'),
            $this->stringArgument('module'),
            (bool) $this->option('force'),
        ));
    }
}
