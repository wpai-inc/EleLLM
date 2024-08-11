<?php

namespace WpAi\EleLLM\Responses;

class ChatMessage
{
    public function __construct(
        public readonly string $role,
        public readonly string $content,
    ) {}
}
