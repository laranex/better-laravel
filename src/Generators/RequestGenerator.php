<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Laranex\BetterLaravel\Str;

class RequestGenerator extends Generator
{
    /**
     * Generate a form request inside a domain and return the generated file path.
     *
     * @throws Exception
     */
    public function generate(string $request, string $domain, bool $force = false): string
    {
        $request = Str::request($request);
        $domain = Str::domain($domain);

        $directoryPath = app_path("Domains/$domain/Requests");
        $filePath = "$directoryPath/$request.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents(), [
            'namespace' => "App\\Domains\\$domain\\Requests",
            'request' => $request,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    public function getStubContents(): string
    {
        return $this->stub('request.php');
    }
}
