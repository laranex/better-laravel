<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;
use Laranex\BetterLaravel\Str;

class FeatureGenerator extends Generator
{
    /**
     * Generate a feature.
     *
     *
     * @throws Exception
     */
    public function generate(string $feature, string $module, bool $force = false): string
    {
        $feature = Str::feature($feature);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Features");
        $filename = "{$feature}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if (File::exists($filePath) && ! $force) {
            $path = Decorator::getRelativePath($filePath);
            throw new Exception("$path already exists!");
        }

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Modules\\{$module}\\Features",
            'feature' => $feature,
        ]);

        if (! File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    private function getStubContents(): string
    {
        return File::get(__DIR__.'/stubs/feature.stub');
    }
}
