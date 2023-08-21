<?php

namespace Laranex\BetterLaravel\Generators;

use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class FeatureGenerator
{
    public function generate($feature, $module): string
    {
        $feature = Str::feature($feature);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/{$module}/Features");
        $filename = "{$feature}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if (File::exists($filePath)) {
            return "Feature already exists: {$filename}";
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

    private function getStubContents()
    {
        return File::get(__DIR__.'/stubs/feature.stub');
    }

    private function replacePlaceholders($content, $replacements)
    {
        foreach ($replacements as $placeholder => $replacement) {
            $content = str_replace("{{{$placeholder}}}", $replacement, $content);
        }

        return $content;
    }
}
