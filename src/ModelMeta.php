<?php

namespace WpAi\EleLLM;

class ModelMeta
{
    public function __construct(
        public readonly int $context_window,
        public readonly float $input_cost,
        public readonly float $output_cost,
        public readonly ?string $name = null,
        public readonly ?int $max_output = null,
    ) {}

    public function toArray(): array
    {
        return [
            'context_window' => $this->context_window,
            'input_cost' => $this->input_cost,
            'output_cost' => $this->output_cost,
        ];
    }
}
