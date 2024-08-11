<?php

namespace WpAi\EleLLM\Requests;

use WpAi\EleLLM\Interfaces\IChatOptionsAdapter;

class ChatOptions
{
    private IChatOptionsAdapter $adapter;

    public function __construct(
        public readonly string $model,
        public readonly ?string $system = null,
    ) {
        $this->adapter = $this->baseAdapter();
    }

    public static function createFromArray(array $options): self
    {
        return new self(...$options);
    }

    public function setAdapter(IChatOptionsAdapter $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'model' => $this->model,
            'system' => $this->system,
        ], fn ($v) => $v !== null);
    }

    public function get(): array
    {
        return $this->adapter->transform($this);
    }

    private function baseAdapter(): IChatOptionsAdapter
    {
        return new class implements IChatOptionsAdapter
        {
            public function transform(ChatOptions $option): array
            {
                return $option->toArray();
            }
        };
    }
}
