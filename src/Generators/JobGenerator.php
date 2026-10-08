<?php

declare(strict_types=1);

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Laranex\BetterLaravel\Str;

class JobGenerator extends Generator
{
    /**
     * Generate a job inside a domain and return the generated file path.
     *
     * @throws Exception
     */
    public function generate(string $job, string $domain, bool $queueable = false, bool $force = false): string
    {
        $this->ensureNameIsNotNested($job, 'job');
        $this->ensureNameIsNotNested($domain, 'domain');

        $job = Str::job($job);
        $domain = Str::domain($domain);

        $directoryPath = app_path("Domains/$domain/Jobs");
        $filePath = "$directoryPath/$job.php";

        $this->throwIfFileExists($filePath, $force);

        $stubContents = $this->replacePlaceholders($this->getStubContents($queueable), [
            'namespace' => "App\\Domains\\$domain\\Jobs",
            'job' => $job,
        ]);

        $this->generateFile($directoryPath, $filePath, $stubContents);

        return $filePath;
    }

    public function getStubContents(bool $queueable = false): string
    {
        return $this->stub($queueable ? 'job.queueable.php' : 'job.php');
    }
}
