<?php

namespace Laranex\BetterLaravel;

class Decorator
{
    public static function getFileGeneratedOutput(string $path): string
    {
        $path = self::getRelativePath($path);

        return "🚀🚀🚀 [$path has been successfully generated!] 🚀🚀🚀";
    }

    public static function getFileGenerationErrorOutput(string $message): string
    {
        return "🚀🚀🚀 [$message] 🚀🚀🚀";
    }

    public static function getRelativePath(string $path): string
    {
        return ltrim(str_replace(base_path(), '', $path), '/');
    }
}
