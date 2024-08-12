<?php

namespace WpAi\EleLLM\Providers\Anthropic;

use Generator;
use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\Exceptions\MalformedRequest;
use WpAi\EleLLM\Exceptions\ProviderModelUndefined;
use WpAi\EleLLM\Interfaces\ClientInterface;
use WpAi\EleLLM\Interfaces\ProviderInterface;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Requests\ChatOptions;
use WpAi\EleLLM\Requests\ChatRequest;

class AnthropicProvider implements ProviderInterface
{
    public static Provider $provider = Provider::ANTHROPIC;

    private ClientInterface $client;

    public function __construct(array $config)
    {
        $this->client = new AnthropicClient($config['api_key'], $config['api_version']);
    }

    public function request(Messages $messages, array $options): string
    {
        return (string) $this->client->request(
            $this->getChatRequest($messages, $options)
        );
    }

    public function stream(Messages $messages, array $options): Generator
    {
        $stream = $this->client->stream($this->getChatRequest($messages, $options));

        foreach ($stream as $chatResponse) {
            yield (string) $chatResponse;
        }
    }

    private function getChatRequest(Messages $messages, array $options): ChatRequest
    {
        $this->validateRequest($messages, $options);

        $options = ChatOptions::createFromArray([
            ...$options, 'system' => $messages->getSystem()->message,
        ])->setAdapter(new AnthropicChatOptionsAdapter);

        return new ChatRequest(
            options: $options->get(),
            messages: $messages->setAdapter(new AnthropicMessagesAdapter)->get(),
        );
    }

    private function validateRequest(Messages $messages, array $options): void
    {
        if (! isset($options['model'])) {
            throw new MalformedRequest('A model is required in the request options.');
        }

        if (! isset($messages->getSystem()->message)) {
            throw new ProviderModelUndefined($this::$provider->name, 'system message');
        }
    }
}
