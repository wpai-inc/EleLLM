<?php

use WpAi\EleLLM\Enums\Model;
use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\ProviderModel;

test('getting meta from a provider model', function () {
    $model = new ProviderModel(Provider::OPENAI, Model::GPT4O_MINI);

    expect($model->meta()->toArray())->toBe([
        'context_window' => 128000,
        'input_cost' => 0.15,
        'output_cost' => 0.60,
    ]);
});
