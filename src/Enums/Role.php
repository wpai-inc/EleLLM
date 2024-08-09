<?php

namespace WpAi\EleLLM\Enums;

enum Role: string
{
    case SYSTEM = 'system';
    case USER = 'user';
    case ASSISTANT = 'assistant';
}
