<?php

namespace Laranex\BetterLaravel;

class Decorator
{
    public static function getGeneratedOutput(string $path): string
    {
        $relativePath = ltrim(str_replace(base_path(), '', $path), '/');

        return "🚀🚀🚀 $relativePath has been successfully generated! 🚀🚀🚀";
    }
}
