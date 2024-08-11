<?php

namespace WpAi\EleLLM\Requests;

class ChatRequest
{
    public function __construct(
        public array $options,
        public array $messages,
    ) {}

    public function toArray(): array
    {
        return [
            ...$this->options,
            'messages' => $this->messages,
        ];
    }
}
