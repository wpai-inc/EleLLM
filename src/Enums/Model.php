<?php

namespace WpAi\EleLLM\Enums;

use WpAi\EleLLM\ModelMeta;
use WpAi\EleLLM\ProviderModel;

enum Model: string
{
    case GPT4O_MINI = 'gpt-4o-mini';
    case GPT4O = 'gpt-4o';
    case GPT4_TURBO = 'gpt-4-turbo';
    case GPT4 = 'gpt-4';
    case GPT35_TURBO = 'gpt-3.5-turbo';
    case TEXT_MODERATION = 'text-moderation-latest';

    public function meta(Provider $provider): ModelMeta
    {
        return (new ProviderModel($provider, $this))->meta();
    }
}
