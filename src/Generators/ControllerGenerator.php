<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
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
        $filename = "$controller.php";
        $filePath = "$directoryPath/$filename";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Modules\\{$module}\\Http",
            'controller' => $controller,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(): string
    {
        return File::get(__DIR__.'/stubs/controller.stub');
    }
}
