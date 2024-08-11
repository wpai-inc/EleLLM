<?php

namespace WpAi\EleLLM\Interfaces;

use WpAi\EleLLM\Message;

interface IMessagesAdapter
{
    public function clientMessage(Message $message): array;
}
