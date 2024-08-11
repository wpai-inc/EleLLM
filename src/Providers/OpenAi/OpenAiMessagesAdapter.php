<?php

namespace WpAi\EleLLM\Providers\OpenAi;

use WpAi\EleLLM\Interfaces\IMessagesAdapter;
use WpAi\EleLLM\Message;

class OpenAiMessagesAdapter implements IMessagesAdapter
{
    public function transform(array $messages): array
    {
        return $messages;
    }

    public function clientMessage(Message $msg): array
    {
        $content = [
            [
                'type' => 'text',
                'text' => $msg->message,
            ],
        ];

        if ($msg->hasVision()) {
            $content[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $msg->vision,
                ],
            ];
        }

        return [
            'role' => $msg->role->value,
            'content' => $content,
        ];
    }
}
