<?php

use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Providers\ProviderFactory;

test('the provider can make a request', function (Provider $provider) {
    $messages = (new Messages)->addSystemMessage('Reply only with "OK"');
    $provider = ProviderFactory::create($provider);
    $response = $provider->request($messages, ['gpt-3.5-turbo']);
    expect((string) $response)->toBe('OK');
})->with([
    'openai' => Provider::OPENAI,
]);
