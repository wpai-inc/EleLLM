<?php

namespace WpAi\EleLLM;

class ModelMeta
{
    public function __construct(
        public int $context_window,
        public float $input_cost,
        public float $output_cost,
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
