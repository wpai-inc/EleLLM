<?php

namespace WpAi\EleLLM\Enums;

use WpAi\EleLLM\ModelMeta;
use WpAi\EleLLM\ProviderModel;
use WpAi\EleLLM\Providers\Anthropic\AnthropicProvider;
use WpAi\EleLLM\Providers\OpenAi\OpenAiProvider;

enum Provider: string
{
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';

    public function meta(Model $model): ModelMeta
    {
        return (new ProviderModel($this, $model))->meta();
    }

    public function getClass(): string
    {
        return match ($this) {
            Provider::OPENAI => OpenAiProvider::class,
            Provider::ANTHROPIC => AnthropicProvider::class,
        };
    }

    public function models(): array
    {
        return match ($this) {
            Provider::OPENAI => [
                Model::GPT4O_MINI,
                Model::GPT4O,
                Model::GPT4_TURBO,
                Model::GPT4,
                Model::GPT35_TURBO,
                Model::TEXT_MODERATION,
            ],
            Provider::ANTHROPIC => [
                Model::CLAUDE3_OPUS,
                Model::CLAUDE3_SONNET,
                Model::CLAUDE35_SONNET,
                Model::CLAUDE3_HAIKU,
            ],
        };
    }

    public function hasModel(Model $model): bool
    {
        return in_array($model, $this->models());
    }
}
