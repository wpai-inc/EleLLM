<?php

use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\IMessagesAdapter;
use WpAi\EleLLM\Message;
use WpAi\EleLLM\Messages;
use WpAi\EleLLM\Providers\OpenAi\OpenAiMessagesAdapter;

test('Messages can only have 1 system', function () {
    $messages = new Messages;

    $messages->addMessage(new Message(Role::SYSTEM, 'system 1'));
    $messages->addMessage(new Message(Role::SYSTEM, 'system 2'));
    $messages->addMessage(new Message(Role::SYSTEM, 'system 3'));

    expect(count($messages))->toBe(1);
});

test('the Messages adapter and encoding', function (?IMessagesAdapter $adapter, array $result) {
    $messages = (new Messages($adapter))
        ->addSystemMessage('greetings')
        ->addUserMessage('hi')
        ->addAssistantMessage('bye');

    expect(json_encode($messages))->toBe(json_encode($result));
})->with([
    'SimpleMessagesAdapter' => [
        'adapter' => null,
        'result' => [
            ['system' => 'greetings'],
            ['user' => 'hi'],
            ['assistant' => 'bye'],
        ],
    ],
    'OpenAiMessagesAdapter' => [
        'adapter' => new OpenAiMessagesAdapter,
        'result' => [
            ['role' => 'system', 'content' => [['type' => 'text', 'text' => 'greetings']]],
            ['role' => 'user', 'content' => [['type' => 'text', 'text' => 'hi']]],
            ['role' => 'assistant', 'content' => [['type' => 'text', 'text' => 'bye']]],
        ],
    ],
]);
