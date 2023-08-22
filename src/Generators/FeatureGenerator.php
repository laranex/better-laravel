<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
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
        $filename = "$feature.php";
        $filePath = "$directoryPath/$filename";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Modules\\$module\\Features",
            'feature' => $feature,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return File::get(__DIR__.'/stubs/feature.stub');
    }
}
