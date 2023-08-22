<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;
use Laranex\BetterLaravel\Str;

class ControllerGenerator extends Generator
{
    /**
     * Generate a controller.
     *
     *
     * @throws Exception
     */
    public function generate(string $controller, string $module, bool $force = false): string
    {
        $controller = Str::controller($controller);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Http");
        $filename = "{$controller}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if (File::exists($filePath) && ! $force) {
            $path = Decorator::getRelativePath($filePath);
            throw new Exception("$path already exists!");
        }

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Modules\\{$module}\\Http",
            'controller' => $controller,
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
        return File::get(__DIR__.'/stubs/controller.stub');
    }
}
