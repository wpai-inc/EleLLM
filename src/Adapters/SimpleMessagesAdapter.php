<?php

namespace WpAi\EleLLM\Adapters;

use WpAi\EleLLM\Interfaces\IMessagesAdapter;
use WpAi\EleLLM\Message;

class SimpleMessagesAdapter implements IMessagesAdapter
{
    public function clientMessage(Message $msg): array
    {
        return [
            $msg->role->value => $msg->message,
        ];
    }
}
