<?php

namespace Laranex\BetterLaravel\Generators;

class Generator
{
    /**
     * Replace placeholders in stubs
     */
    public function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $placeholder => $replacement) {
            if ($placeholder === 'namespace') {
                $replacement = str_replace('/', '\\', $replacement);
            }

            $content = str_replace("{{{$placeholder}}}", $replacement, $content);
        }

        return $content;
    }
}
