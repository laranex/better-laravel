<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class RouteGenerator extends Generator
{
    /**
     * Generate a route.
     *
     *
     * @throws Exception
     */
    public function generate(string $route, string $versionOrDirectory = '', string $routeFileType = 'web', bool $force = false): string
    {
        $route = Str::route($route);
        $versionOrDirectory = Str::directory($versionOrDirectory);

        $versionOrDirectory = $versionOrDirectory ? "/$versionOrDirectory" : '';

        $directoryPath = base_path("routes/$routeFileType$versionOrDirectory");
        $filename = "$route.php";
        $filePath = "$directoryPath/$filename";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents();

        $stubContents = $this->replacePlaceholders($stubContents, [
            'route' => $route,
            'versionOrDirectory' => $versionOrDirectory,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    private function getStubContents(): string
    {
        return File::get(__DIR__.'/stubs/route.stub');
    }
}
