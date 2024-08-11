<?php

namespace WpAi\EleLLM\Responses;

class Usage
{
    public function __construct(
        public readonly int $promptTokens,
        public readonly int $completionTokens,
        public readonly int $totalTokens,
    ) {}
}
