<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;

abstract class Generator
{
    /**
     * Replace placeholders in stubs
     */
    public function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            if ($placeholder === 'namespace') {
                $replacement = str_replace('/', '\\', $replacement);
            }

            $content = str_replace("{{{$placeholder}}}", $replacement, $content);
        }

        return $content;
    }

    /**
     * Throws exception if the given file exists and force options is false
     *
     * @throws Exception
     */
    public function throwIfFileExists(string $filePath, bool $force = false): void
    {
        if (File::exists($filePath) && ! $force) {
            $path = Decorator::getRelativePath($filePath);
            throw new Exception("$path already exists!");
        }
    }
}
