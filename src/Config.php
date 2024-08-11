<?php

namespace WpAi\EleLLM;

use WpAi\EleLLM\Enums\Provider;

/**
 * Configuration class for the EleLLM package.
 * Loads the configuration needed for each provider,
 * by their namepsaces.
 */
class Config
{
    /**
     * Get the configuration for a provider.
     */
    public static function getProviderConfig(Provider $provider): array
    {
        return match ($provider) {
            Provider::OPENAI => [
                'api_key' => $_ENV['OPENAI_API_KEY'],
                'organization' => $_ENV['OPENAI_ORGANIZATION'],
                'request_timeout' => $_ENV['OPENAI_REQUEST_TIMEOUT'] ?? 30,
            ],
            Provider::ANTHROPIC => [
                'api_key' => $_ENV['ANTHROPIC_API_KEY'],
                'api_version' => $_ENV['ANTHROPIC_API_VERSION'] ?? null,
            ],
        };
    }
}
