<?php

namespace Laranex\BetterLaravel;

use Laranex\BetterLaravel\Commands\ControllerMakeCommand;
use Laranex\BetterLaravel\Commands\FeatureMakeCommand;
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
            ->hasCommands([
                ControllerMakeCommand::class,
                FeatureMakeCommand::class,
            ]);
    }
}
