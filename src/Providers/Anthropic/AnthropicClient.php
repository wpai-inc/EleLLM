<?php

namespace WpAi\EleLLM\Providers\Anthropic;

use Generator;
use WpAi\Anthropic\AnthropicAPI;
use WpAi\Anthropic\Resources\MessagesResource;
use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Requests\ChatRequest;
use WpAi\EleLLM\Responses\ChatChoice;
use WpAi\EleLLM\Responses\ChatMessage;
use WpAi\EleLLM\Responses\ChatResponse;

class AnthropicClient implements ClientInterface
{
    private AnthropicAPI $client;

    public function __construct(string $apiKey, ?string $apiVersion = null)
    {
        $this->client = new AnthropicAPI($apiKey, $apiVersion);
    }

    /**
     * @todo: Anthropic SDK needs work, this is cumbersome
     */
    public function request(ChatRequest $request): ChatResponse
    {
        $result = $this->makeRequest($request)->create($request->toArray());

        $choices = [];
        foreach ($result->content as $k => $c) {
            $choices[] = new ChatChoice(
                index: $k,
                message: new ChatMessage(
                    role: Role::ASSISTANT->value,
                    content: $c['text']
                ),
                finishReason: $result?->stopReason,
            );
        }

        $response = (new ChatResponse(
            choices: $choices,
            id: $result->id,
            model: $result->model,
        ));

        if ($usage = $result->usage) {
            $total = $usage->inputTokens + $usage->outputTokens;
            $response->setUsage($usage->inputTokens, $usage->outputTokens, $total);
        }

        return $response;
    }

    public function stream(ChatRequest $request): Generator
    {
        $stream = $this->makeRequest($request)->stream($request->toArray());

        $content = '';
        foreach ($stream->getIterator() as $response) {
            $type = $response['type'];

            switch ($type) {
                case 'message_start':
                    // $this->inputTokens = $response['message']['usage']['input_tokens'];
                    break;
                case 'content_block_delta':
                    $content .= $response['delta']['text'];
                    yield $content;
                    break;
                case 'message_delta':
                    // $this->outputTokens = $response['usage']['output_tokens'];
                    break;
            }
        }
    }

    private function makeRequest(ChatRequest $request): MessagesResource
    {
        return $this->client->messages()
            ->model($request->model)
            ->maxTokens($request->maxTokens ?? 1024)
            ->system($request->system)
            ->messages($request->messages);
    }
}
