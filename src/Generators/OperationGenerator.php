<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Laranex\BetterLaravel\Str;

class OperationGenerator extends Generator
{
    /**
     * Generate an operation inside a module and return the generated file path.
     *
     * @throws Exception
     */
    public function generate(string $operation, string $module, bool $force = false): string
    {
        $this->ensureNameIsNotNested($operation, 'operation');
        $this->ensureNameIsNotNested($module, 'module');

        $operation = Str::operation($operation);
        $module = Str::module($module);

        $directoryPath = app_path("Modules/$module/Operations");
        $filePath = "$directoryPath/$operation.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Modules\\$module\\Operations",
            'operation' => $operation,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    public function getStubContents(): string
    {
        return $this->stub('operation.php');
    }
}
