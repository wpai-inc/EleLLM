<?php

namespace WpAi\EleLLM;

class EleLLM
{
    public Prompt $prompt;

    public function __construct(
        private ?string $promptFileDir = null,
        private ?string $promptCacheDir = null,
    ) {
        if (is_null($this->promptFileDir)) {
            $this->promptFileDir = __DIR__.'/prompts';
        }

        if (is_null($this->promptCacheDir)) {
            $this->promptCacheDir = __DIR__.'/cache';
        }

        $this->prompt = new Prompt($this->promptFileDir, $this->promptCacheDir);
    }
}
