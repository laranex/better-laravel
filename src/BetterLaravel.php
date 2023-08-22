<?php

namespace Laranex\BetterLaravel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BetterLaravel
{
    public static function getAllFilesOfADirectory(string $directory, string $extension = ''): array
    {
        $files = [];

        if (! is_dir($directory)) {
            return $files;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && (! $extension || $fileInfo->getExtension() === $extension)) {
                $files[] = $fileInfo->getPathname();
            }
        }

        return $files;
    }
}
