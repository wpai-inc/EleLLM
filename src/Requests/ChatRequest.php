<?php

namespace WpAi\EleLLM\Requests;

class ChatRequest
{
    public function __construct(
        private array $options,
        private array $messages,
    ) {}

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return $this->options[$name] ?? $this->messages[$name] ?? null;
    }

    public function toArray(): array
    {
        return [
            ...$this->options,
            'messages' => $this->messages,
        ];
    }
}
