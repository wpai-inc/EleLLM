<?php

namespace WpAi\EleLLM\Interfaces;

use WpAi\EleLLM\Message;

interface IMessagesAdapter
{
    public function transform(array $messages): array;

    public function clientMessage(Message $message): array;
}
