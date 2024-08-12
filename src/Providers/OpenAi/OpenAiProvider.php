<?php

namespace WpAi\EleLLM\Providers\OpenAi;

use Generator;
use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Interfaces\ProviderInterface;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Requests\ChatOptions;
use WpAi\EleLLM\Requests\ChatRequest;

/**
 * Makes the request to a model for a given provider.
 * The provider is responsible for formatting a request
 * to for the model.
 *
 * This is as opposed to the client which is responsible
 * for making the request to a particular endpoint.
 *
 * Eg) An OpenAiProvider can be used to make a request to
 * a client other than the default OpenAI API. It's common
 * to have an OpenAi Request spec used elsewhere than their
 * own OpenAI APIs.
 */
class OpenAiProvider implements ProviderInterface
{
    public static Provider $provider = Provider::OPENAI;

    private ClientInterface $client;

    public function __construct(array $config)
    {
        $this->client = new OpenAiClient($config['api_key']);
    }

    public function request(Messages $messages, array $options): string
    {
        return (string) $this->client->request(
            $this->getChatRequest($messages, $options)
        );
    }

    public function stream(Messages $messages, array $options): Generator
    {
        $options['stream_options'] = ['include_usage' => true];
        $stream = $this->client->stream($this->getChatRequest($messages, $options));

        foreach ($stream as $response) {
            yield (string) $response;
        }
    }

    private function getChatRequest(Messages $messages, array $options): ChatRequest
    {
        $options = ChatOptions::createFromArray($options)->setAdapter(new OpenAiChatOptionsAdapter);

        return new ChatRequest(
            options: $options->get(),
            messages: $messages->setAdapter(new OpenAiMessagesAdapter)->get(),
        );
    }
}
