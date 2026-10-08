<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;

abstract class Generator
{
    /**
     * Replace {{placeholder}} tokens in the stub contents.
     *
     * @param  array<string, string>  $replacements
     */
    public function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            if ($placeholder === 'namespace') {
                $replacement = str_replace('/', '\\', $replacement);
            }

            $content = str_replace('{{'.$placeholder.'}}', $replacement, $content);
        }

        return $content;
    }

    /**
     * Throw when the target file exists and the force option is off.
     *
     * @throws Exception
     */
    public function throwIfFileExists(string $filePath, bool $force = false): void
    {
        if (File::exists($filePath) && ! $force) {
            throw new Exception(Decorator::getRelativePath($filePath).' already exists!');
        }
    }

    /**
     * Write the replaced stub contents into a file, creating the directory when needed.
     */
    public function generateFile(string $directoryPath, string $filePath, string $stubContents): void
    {
        if (! File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, $stubContents);
    }

    /**
     * Read a stub, preferring the copy published to resources/stubs/vendor/better-laravel.
     */
    protected function stub(string $name): string
    {
        $stubFile = resource_path("stubs/vendor/better-laravel/$name.stub");

        if (! File::exists($stubFile)) {
            $stubFile = dirname(__DIR__, 2)."/resources/stubs/$name.stub";
        }

        return File::get($stubFile);
    }
}
