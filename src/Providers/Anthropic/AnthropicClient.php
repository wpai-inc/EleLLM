<?php

namespace WpAi\EleLLM\Providers\Anthropic;

use Generator;
use WpAi\Anthropic\AnthropicAPI;
use WpAi\Anthropic\Resources\MessagesResource;
use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Requests\ChatRequest;
use WpAi\EleLLM\Responses\ChatChoice;
use WpAi\EleLLM\Responses\ChatChoices;
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

        $choices = new ChatChoices;
        foreach ($result->content as $k => $c) {
            $choices->addChoice(new ChatChoice(
                index: $k,
                message: new ChatMessage(
                    role: Role::ASSISTANT->value,
                    content: $c['text']
                ),
                finishReason: $result?->stopReason,
            ));
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

        $choices = new ChatChoices;
        foreach ($stream as $response) {
            $id = null;
            $model = null;
            $usage = [];
            $finishReason = null;
            $role = null;
            switch ($response['type']) {
                case 'message_start':
                    $id = $response['message']['id'];
                    $model = $response['message']['model'];
                    $role = Role::from($response['message']['role'])->value;
                    $usage = array_merge($usage, $response['message']['usage']);
                    break;
                case 'content_block_delta':
                    $choices->append(new ChatChoice(
                        index: $response['index'],
                        message: new ChatMessage(
                            role: $role,
                            content: $response['delta']['text'],
                        ),
                        finishReason: $finishReason,
                    ));
                    break;
                case 'message_delta':
                    $finishReason = $response['delta']['stop_reason'];
                    $usage = array_merge($usage, $response['usage']);
                    break;
            }

            $chatResponse = new ChatResponse(
                choices: $choices,
                id: $id,
                model: $model,
            );

            if (isset($usage['input_tokens']) && isset($usage['output_tokens'])) {
                $chatResponse->setUsage($usage['input_tokens'], $usage['output_tokens'], $usage['input_tokens'] + $usage['output_tokens']);
            }

            yield $chatResponse;
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
