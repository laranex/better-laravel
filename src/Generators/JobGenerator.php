<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Str;

class JobGenerator extends Generator
{
    /**
     * Generate a job.
     *
     *
     * @throws Exception
     */
    public function generate(string $job, string $domain, bool $queueable = false, bool $force = false): string
    {
        $job = Str::job($job);
        $domain = Str::domain($domain);

        $directoryPath = app_path("Domains/{$domain}/Jobs");
        $filename = "{$job}.php";
        $filePath = "{$directoryPath}/{$filename}";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->getStubContents($queueable);

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Domains\\{$domain}\\Jobs",
            'job' => $job,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    public function getStubContents(bool $queueable): string
    {
        $stubFile = $queueable ? 'job.queueable.stub' : 'job.stub';

        return File::get(__DIR__.'/stubs/'.$stubFile);
    }
}
