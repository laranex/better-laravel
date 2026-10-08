<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class BetterLaravel
{
    /**
     * Recursively list the files of a directory, optionally filtered by extension.
     *
     * @return array<int, string>
     */
    public static function getAllFilesOfADirectory(string $directory, string $extension = ''): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $files = [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        );

        /** @var SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && ($extension === '' || $fileInfo->getExtension() === $extension)) {
                $files[] = $fileInfo->getPathname();
            }
        }

        sort($files);

        return $files;
    }
}
