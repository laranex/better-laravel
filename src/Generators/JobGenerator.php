<?php

namespace Laranex\BetterLaravel\Generators;

use Exception;
use Illuminate\Support\Facades\File;
use Laranex\BetterLaravel\Decorator;
use Laranex\BetterLaravel\Str;

class JobGenerator extends Generator
{
    /**
     * Generate a job.
     *
     *
     * @throws Exception
     */
    public function generate(string $job, string $module, bool $queueable = false, bool $force = false): string
    {
        $job = Str::job($job);
        $module = Str::module($module);

        $directoryPath = app_path("Domains/{$module}/Jobs");
        $filename = "{$job}.php";
        $filePath = "{$directoryPath}/{$filename}";

        if (File::exists($filePath) && ! $force) {
            $path = Decorator::getRelativePath($filePath);
            throw new Exception("$path already exists!");
        }

        $stubContents = $this->getStubContents($queueable);

        $stubContents = $this->replacePlaceholders($stubContents, [
            'namespace' => "App\\Domains\\{$module}\\Jobs",
            'job' => $job,
        ]);

        if (! File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, $stubContents);

        return $filePath;
    }

    /**
     * Get the appropriate stub contents.
     */
    private function getStubContents(bool $queueable): string
    {
        $stubFile = $queueable ? 'job.queueable.stub' : 'job.stub';

        return File::get(__DIR__.'/stubs/'.$stubFile);
    }

    /**
     * Replace placeholders in stubs
     */
    private function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            $content = str_replace("{{{$placeholder}}}", $replacement, $content);
        }

        return $content;
    }
}
