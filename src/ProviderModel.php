<?php

namespace WpAi\EleLLM;

use WpAi\EleLLM\Enums\Model;
use WpAi\EleLLM\Enums\Provider;

class ProviderModel
{
    public function __construct(private Provider $provider, private Model $model) {}

    /**
     * Get the meta data for the model
     * Meta depends on the model and provider
     */
    public function meta(): ModelMeta
    {
        return match ($this->model) {
            Model::GPT4O_MINI => match ($this->provider) {
                default => new ModelMeta(
                    name: 'GPT-4 Mini',
                    context_window: 128000,
                    input_cost: 0.15,
                    output_cost: 0.60,
                ),
            },
            Model::GPT4O => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 128000,
                    input_cost: 5.00,
                    output_cost: 15.00,
                ),
            },
            Model::GPT4_TURBO => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 128000,
                    input_cost: 10.00,
                    output_cost: 30.00,
                ),
            },
            Model::GPT4 => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 8192,
                    input_cost: 30.00,
                    output_cost: 60.00,
                ),
            },
            Model::GPT35_TURBO => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 16385,
                    input_cost: 0.50,
                    output_cost: 1.50,
                ),
            },
            Model::TEXT_MODERATION => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 8192,
                    input_cost: 0.00,
                    output_cost: 0.00,
                ),
            },
            Model::CLAUDE3_OPUS => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 200000,
                    input_cost: 15.00,
                    output_cost: 75.00,
                    max_output: 4096,
                ),
            },
            Model::CLAUDE3_SONNET => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 200000,
                    input_cost: 3.00,
                    output_cost: 15.00,
                    max_output: 4096,
                ),
            },
            Model::CLAUDE35_SONNET => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 200000,
                    input_cost: 3.00,
                    output_cost: 15.00,
                    max_output: 4096,
                ),
            },
            Model::CLAUDE3_HAIKU => match ($this->provider) {
                default => new ModelMeta(
                    context_window: 200000,
                    input_cost: 0.25,
                    output_cost: 1.25,
                    max_output: 4096,
                ),
            },
        };
    }
}
