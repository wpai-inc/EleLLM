<?php

use WpAi\EleLLM\EleLLM;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Providers\OpenAi\OpenAiMessagesAdapter;

require __DIR__.'/../vendor/autoload.php';

// header('Content-Type: text/plain');

$llm = new EleLLM(
    __DIR__.'/prompts',
    __DIR__.'/../cache',
);

$llm->prompt->share(['color' => 'red']);

$request = $llm->request();

$messages = (new Messages(new OpenAiMessagesAdapter))
    ->addSystemMessage('greetings')
    ->addSystemMessage('greetings 2')
    ->addSystemMessage($llm->prompt->file('matrix'))
    ->addUserMessage('hi')
    ->addAssistantMessage('bye');

echo '<pre>';
echo json_encode($messages, JSON_PRETTY_PRINT);
echo '</pre>';
