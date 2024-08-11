<?php

namespace WpAi\EleLLM;

use WpAi\EleLLM\Interfaces\ProviderInterface;

class LlmRequest
{
    public function __construct(
        public readonly ProviderInterface $provider,
        private Messages $messages
    ) {}
}
