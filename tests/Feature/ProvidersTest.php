<?php

use WpAi\EleLLM\Enums\Model;
use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Providers\ProviderFactory;

test('the provider can make a request', function (Provider $provider, Model $model) {
    $messages = (new Messages)->addSystemMessage('Reply only with "OK"');
    $provider = ProviderFactory::create($provider);
    $response = $provider->request($messages, ['model' => $model->value]);

    $stream = $provider->stream($messages, ['model' => $model->value]);

    $streamResponse = '';
    foreach ($stream as $content) {
        $streamResponse = $content;
    }

    expect((string) $response)->toBe('OK');
    expect($streamResponse)->toBe('OK');
})->with([
    'openai' => [
        'provider' => Provider::OPENAI,
        'model' => Model::GPT35_TURBO,
    ],
    'anthropic' => [
        'provider' => Provider::ANTHROPIC,
        'model' => Model::CLAUDE3_HAIKU,
    ],
]);
