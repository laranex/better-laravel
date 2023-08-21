<?php

namespace Laranex\BetterLaravel;

use Laranex\BetterLaravel\Commands\BetterLaravelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BetterLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('better-laravel')
            ->hasConfigFile()
            ->hasCommand(BetterLaravelCommand::class);
    }
}
