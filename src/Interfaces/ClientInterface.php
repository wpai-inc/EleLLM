<?php

namespace WpAi\EleLLM\Interfaces;

use OpenAI\Responses\StreamResponse;
use WpAi\EleLLM\Requests\ChatRequest;
use WpAi\EleLLM\Responses\ChatResponse;

interface ClientInterface
{
    public function request(ChatRequest $request): ChatResponse;

    public function stream(ChatRequest $request): StreamResponse;
}