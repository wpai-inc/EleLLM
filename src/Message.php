<?php

namespace WpAi\EleLLM;

use Illuminate\Support\Str;
use WpAi\EleLLM\Enums\Role;

class Message
{
    public function __construct(
        public Role $role,
        public string $message,
        public ?string $vision = null
    ) {}

    public function interpolate(array $interpolate): self
    {
        $interpolated = Str::of($this->message)->replaceMatches(
            "/\{([a-zA-Z0-9_]+)\}/",
            function ($match) use ($interpolate) {
                if (isset($interpolate[$match[1]])) {
                    return is_scalar($interpolate[$match[1]]) ? (string) $interpolate[$match[1]] : '';
                }

                return null;
            }
        );

        $this->message = $interpolated;

        return $this;
    }

    public function hasVision(): bool
    {
        return ! empty($this->vision);
    }

    public function isSystem(): bool
    {
        return $this->role === Role::SYSTEM;
    }
}
