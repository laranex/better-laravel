<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class TestGenerator extends Generator
{
    /**
     * Generate a job.
     *
     * @throws Exception
     */
    public function generate(string $test, string $domain, bool $isUnit = false, bool $force = false, bool $dry = false): string
    {
        if ($isUnit) {
            $test = Str::job($test);
            $domain = Str::domain($domain);
        } else {
            $test = Str::feature($test);
            $domain = Str::module($domain);
        }

        $test = Str::test($test);

        $directoryPath = base_path('tests/'.($isUnit ? 'Unit/Domains/' : 'Feature/Modules/').$domain);
        $filename = "{$test}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if ($dry) {
            return $filePath;
        }

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents($isUnit);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(bool $isUnit): string
    {
        $filePart = $isUnit ? '.unit' : '.feature';

        $stubFile = resource_path("stubs/vendor/better-laravel/test$filePart.php.stub");
        if (! File::exists($stubFile)) {
            $stubFile = __DIR__."/../../resources/stubs/test$filePart.php.stub";
        }

        return File::get($stubFile);
    }
}
