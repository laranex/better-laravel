<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Laranex\BetterLaravel\Str;

class FeatureGenerator extends Generator
{
    /**
     * Generate a feature inside a module and return the generated file path.
     *
     * @throws Exception
     */
    public function generate(string $feature, string $module, bool $force = false): string
    {
        $this->ensureNameIsNotNested($feature, 'feature');
        $this->ensureNameIsNotNested($module, 'module');

        $feature = Str::feature($feature);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/$module/Features");
        $filePath = "$directoryPath/$feature.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\$module\\Features",
            'feature' => $feature,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    public function getStubContents(): string
    {
        return $this->stub('feature.php');
    }
}
