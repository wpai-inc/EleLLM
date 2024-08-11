<?php

namespace WpAi\EleLLM\Interfaces;

use Generator;
use WpAi\EleLLM\Messages;

interface ProviderInterface
{
    public function request(Messages $messages, array $options): string;

    public function stream(Messages $messages, array $options): Generator;
}
