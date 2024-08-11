<?php

namespace WpAi\EleLLM\Providers\Anthropic;

use WpAi\EleLLM\Interfaces\IChatOptionsAdapter;
use WpAi\EleLLM\Requests\ChatOptions;

class AnthropicChatOptionsAdapter implements IChatOptionsAdapter
{
    public function transform(ChatOptions $option): array
    {
        return $option->toArray();
    }
}
