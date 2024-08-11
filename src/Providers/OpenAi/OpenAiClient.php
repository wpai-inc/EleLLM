<?php

namespace WpAi\EleLLM\Providers\OpenAi;

use OpenAI;
use OpenAI\Client;
use OpenAI\Responses\StreamResponse;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Requests\ChatRequest;
use WpAi\EleLLM\Responses\ChatResponse;

class OpenAiClient implements ClientInterface
{
    private Client $client;

    public function __construct(private string $apiKey)
    {
        $this->client = OpenAI::client($this->apiKey);
    }

    public function request(ChatRequest $request): ChatResponse
    {
        $result = $this->client->chat()->create($request->toArray());

        $response = (new ChatResponse(
            choices: $result->choices,
            id: $result->id,
            object: $result->object,
            timestamp: $result->created,
            model: $result->model,
            systemFingerprint: $result->systemFingerprint,
        ));

        if ($usage = $result->usage) {
            $response->setUsage($usage->promptTokens, $usage->completionTokens, $usage->totalTokens);
        }

        return $response;
    }

    public function stream(ChatRequest $request): StreamResponse
    {
        return $this->client->completions()->createStreamed($request->toArray());
    }
}
