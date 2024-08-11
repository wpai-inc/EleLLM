<?php

namespace WpAi\EleLLM\Enums;

use WpAi\EleLLM\ModelMeta;
use WpAi\EleLLM\ProviderModel;
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
        };
    }
}
