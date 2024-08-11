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
    case CLAUDE3_OPUS = 'claude-3-opus-20240229';
    case CLAUDE3_SONNET = 'claude-3-sonnet-20240229';
    case CLAUDE35_SONNET = 'claude-3-5-sonnet-20240620';
    case CLAUDE3_HAIKU = 'claude-3-haiku-20240307';

    public function meta(Provider $provider): ModelMeta
    {
        return (new ProviderModel($provider, $this))->meta();
    }
}
