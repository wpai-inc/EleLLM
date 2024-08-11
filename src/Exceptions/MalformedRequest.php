<?php

namespace WpAi\EleLLM\Exceptions;

use Exception;

class MalformedRequest extends Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct(! empty($message) ? $message : 'The request is malformed');
    }
}
