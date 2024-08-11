<?php

namespace WpAi\EleLLM\Providers\Anthropic;

use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\IMessagesAdapter;
use WpAi\EleLLM\Message;

class AnthropicMessagesAdapter implements IMessagesAdapter
{
    public function transform(array $messages): array
    {
        $filtered = array_filter($messages, fn (Message $msg) => ! $msg->isSystem());

        if (empty($filtered)) {
            return [new Message(Role::USER, 'n/a')];
        }

        return $filtered;
    }

    public function clientMessage(Message $msg): array
    {
        return [
            'role' => $msg->role->value,
            'content' => $msg->message,
        ];
    }
}
