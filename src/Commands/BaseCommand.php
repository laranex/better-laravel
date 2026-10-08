<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Laranex\BetterLaravel\Decorator;
use Throwable;

abstract class BaseCommand extends Command
{
    /**
     * Run the generator and turn its outcome into console output and an exit code.
     *
     * @param  callable(): string  $generate
     */
    protected function generate(callable $generate): int
    {
        try {
            $this->printFileGeneratedOutput($generate());
        } catch (Throwable $exception) {
            $this->printFileGenerationErrorOutput($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Print pretty output once a file has been generated.
     */
    public function printFileGeneratedOutput(string $output): void
    {
        $this->info(Decorator::getFileGeneratedOutput($output));
        $this->comment(Inspiring::quote());
    }

    /**
     * Print pretty output when file generation fails.
     */
    public function printFileGenerationErrorOutput(string $output): void
    {
        $this->error(Decorator::getFileGenerationErrorOutput($output));
    }

    /**
     * Warn that generated route files are not loaded while routes are disabled.
     */
    public function printDisableRoutesWarning(): void
    {
        $this->error(Decorator::getDisableRoutesWarning());
    }

    /**
     * Read a required string argument.
     */
    protected function stringArgument(string $key): string
    {
        $value = $this->argument($key);

        return is_string($value) ? $value : '';
    }
}
