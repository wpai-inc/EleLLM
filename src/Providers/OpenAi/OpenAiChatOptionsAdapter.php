<?php

namespace WpAi\EleLLM\Providers\OpenAi;

use WpAi\EleLLM\Interfaces\IChatOptionsAdapter;
use WpAi\EleLLM\Requests\ChatOptions;

class OpenAiChatOptionsAdapter implements IChatOptionsAdapter
{
    public function transform(ChatOptions $option): array
    {
        return $option->toArray();
    }
}
