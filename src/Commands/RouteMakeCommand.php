<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Laranex\BetterLaravel\Generators\RouteGenerator;

class RouteMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'better:route
                        {route : Route file name}
                        {versionOrDirectory? : API version or Directory}
                        {--API|api : Generate API route file}
                        {--F|force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route file';

    /**
     * Execute the console command.
     */
    public function handle(RouteGenerator $generator, ConfigRepository $config): int
    {
        $exitCode = $this->generate(fn (): string => $generator->generate(
            $this->stringArgument('route'),
            $this->stringArgument('versionOrDirectory'),
            (bool) $this->option('api') ? 'api' : 'web',
            (bool) $this->option('force'),
        ));

        if (! (bool) $config->get('better-laravel.enable_routes', true)) {
            $this->printDisableRoutesWarning();
        }

        return $exitCode;
    }
}
