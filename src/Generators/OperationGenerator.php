<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class OperationGenerator extends Generator
{
    /**
     * Generate a feature.
     *
     *
     * @throws Exception
     */
    public function generate(string $operation, string $module, bool $force = false, bool $dry = false): string
    {
        $operation = Str::operation($operation);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Operations");
        $filename = "$operation.php";
        $filePath = "$directoryPath/$filename";

        if ($dry) {
            return $filePath;
        }

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Modules\\$module\\Operations",
            'operation' => $operation,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        $stubFile = resource_path('stubs/vendor/better-laravel/operation.php.stub');
        if (! File::exists($stubFile)) {
            $stubFile = __DIR__.'/../../resources/stubs/operation.php.stub';
        }

        return File::get($stubFile);
    }
}
