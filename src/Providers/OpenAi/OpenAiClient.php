<?php

namespace WpAi\EleLLM\Providers\OpenAi;

use Generator;
use OpenAI;
use OpenAI\Client;
use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Requests\ChatRequest;
use WpAi\EleLLM\Responses\ChatChoice;
use WpAi\EleLLM\Responses\ChatChoices;
use WpAi\EleLLM\Responses\ChatMessage;
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

        $choices = array_map(function ($c) {
            $role = Role::from($c->message->role);

            return new ChatChoice(
                index: $c->index,
                message: new ChatMessage(
                    role: $role->value,
                    content: $c->message->content,
                ),
                finishReason: $c->finishReason,
            );
        }, $result->choices);

        $response = (new ChatResponse(
            choices: ChatChoices::create($choices),
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

    /**
     * @todo: implement usage
     * not currently showing in API response
     */
    public function stream(ChatRequest $request): Generator
    {
        $stream = $this->client->chat()->createStreamed($request->toArray());

        $choices = new ChatChoices;
        foreach ($stream as $response) {
            if ($response->choices) {
                foreach ($response->choices as $c) {
                    $choices->append(new ChatChoice(
                        index: $c->index,
                        message: new ChatMessage(
                            role: $c->delta->role,
                            content: $c->delta->content,
                        ),
                        finishReason: $c->finishReason,
                    ));
                }
            }

            yield new ChatResponse(
                choices: $choices,
                id: $response->id,
                object: $response->object,
                timestamp: $response->created,
                model: $response->model,
            );
        }
    }
}
