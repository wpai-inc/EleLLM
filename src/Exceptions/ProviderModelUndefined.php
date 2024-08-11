<?php

namespace WpAi\EleLLM\Exceptions;

use Exception;

class ProviderModelUndefined extends Exception
{
    public function __construct(string $provider, string $model)
    {
        parent::__construct("Model $model is not defined in the $provider provider.");
    }
}
