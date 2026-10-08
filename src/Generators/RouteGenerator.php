<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Laranex\BetterLaravel\Str;

class RouteGenerator extends Generator
{
    /**
     * Generate a route file under routes/{web|api}[/version] and return the generated file path.
     *
     * @throws Exception
     */
    public function generate(string $route, string $versionOrDirectory = '', string $routeFileType = 'web', bool $force = false): string
    {
        $route = Str::route($route);
        $versionOrDirectory = Str::directory($versionOrDirectory);
        $versionOrDirectory = $versionOrDirectory !== '' ? "/$versionOrDirectory" : '';

        $directoryPath = base_path("routes/$routeFileType$versionOrDirectory");
        $filePath = "$directoryPath/$route.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'route' => $route,
            'versionOrDirectory' => $versionOrDirectory,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    public function getStubContents(): string
    {
        return $this->stub('route.php');
    }
}
