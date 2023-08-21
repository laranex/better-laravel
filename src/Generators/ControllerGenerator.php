<?php

namespace Laranex\BetterLaravel\Generators;

use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class ControllerGenerator
{
    public function generate($controller, $module, $force = false): string
    {
        $controller = Str::controller($controller);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Http");
        $filename = "{$controller}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if (File::exists($filePath) && ! $force) {
            return "Feature already exists: {$filename}";
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

    private function getStubContents()
    {
        return File::get(__DIR__.'/stubs/controller.stub');
    }

    private function replacePlaceholders($content, $replacements)
    {
        foreach ($replacements as $placeholder => $replacement) {
            $content = str_replace("{{{$placeholder}}}", $replacement, $content);
        }

        return $content;
    }
}
