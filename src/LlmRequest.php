<?php

namespace WpAi\EleLLM;

use WpAi\EleLLM\Enums\Role;

class LlmRequest
{
    private Messages $messages;

    private array $interpolate = [];

    private ?string $systemPath = null;

    private string $system = '';

    public function __construct()
    {
        $this->messages = new Messages;
    }

    public function setMessages(Messages $convo): self
    {
        $this->messages = $convo;

        return $this;
    }

    public function data(array $data): self
    {
        $this->interpolate($data);

        return $this;
    }

    public function interpolate(array $interpolate): self
    {
        $this->interpolate = array_merge($this->interpolate, $interpolate);

        return $this;
    }

    public function system(string $prompt): self
    {
        if ($this->hasPromptFile($prompt)) {
            $this->systemPath = $prompt;
        } else {
            $this->system = $prompt;
        }

        return $this;
    }

    public function addMessage(Role $role, string $msg, ?string $vision = null)
    {
        $this->messages->addMessage(new Message($role, $msg, $vision));

        return $this;
    }

    public function addUserMessage(string $msg, ?string $vision = null)
    {
        $this->addMessage(Role::USER, $msg, $vision);

        return $this;
    }

    public function addAssistantMessage(string $msg)
    {
        $this->addMessage(Role::ASSISTANT, $msg);

        return $this;
    }

    public function getConvo(): Messages
    {
        $renderedSystem = $this->getPromptContent($this->systemPath ?? $this->system);
        $this->messages->addSystem($renderedSystem);

        if (! empty($this->interpolate)) {
            $this->messages->each(
                fn ($message) => $message->interpolate($this->interpolate)
            );
        }

        return $this->messages;
    }

    private function hasPromptFile(string $path): bool
    {
        return Prompt::has($path) || null;
    }

    private function getPromptContent(string $prompt): string
    {
        if (Prompt::has($prompt)) {
            return Prompt::render($prompt, $this->interpolate);
        }

        return Prompt::renderFromString($prompt, $this->interpolate);
    }
}
