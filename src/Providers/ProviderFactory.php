<?php

namespace WpAi\EleLLM\Providers;

use WpAi\EleLLM\Config;
use WpAi\EleLLM\Enums\Provider;
use WpAi\EleLLM\Interfaces\ProviderInterface;

class ProviderFactory
{
    public static function create(Provider $provider): ProviderInterface
    {
        $class = $provider->getClass();
        $config = Config::getProviderConfig($provider);

        return new $class($config);
    }
}
