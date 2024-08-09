<?php

use WpAi\EleLLM\EleLLM;

test('the base EleLLM class', function () {
    $llm = new EleLLM(
        promptFileDir: __DIR__.'/../prompts',
        promptCacheDir: __DIR__.'/../../cache',
    );

    expect($llm)->toBeInstanceOf(EleLLM::class);
});
