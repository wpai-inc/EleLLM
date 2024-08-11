<?php

namespace WpAi\EleLLM\Interfaces;

use WpAi\EleLLM\Requests\ChatOptions;

interface IChatOptionsAdapter
{
    public function transform(ChatOptions $option): array;
}
