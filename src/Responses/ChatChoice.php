<?php

namespace WpAi\EleLLM\Responses;

class ChatChoice
{
    public function __construct(
        public readonly int $index,
        public readonly ChatMessage $message,
        public readonly ?string $finishReason = null,
    ) {}

    public function getContent(): string
    {
        return $this->message->content;
    }
}
