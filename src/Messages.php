<?php

namespace WpAi\EleLLM;

use Illuminate\Support\Collection;
use JsonSerializable;
use WpAi\EleLLM\Enums\Role;

class Messages implements JsonSerializable
{
    private $messages = [];

    private int $limit = 10;

    /**
     * Adds the system messages as the first message in the messages
     */
    public function addSystem(string $system): self
    {
        $systemMsg = new Message(Role::SYSTEM, $system);

        $this->messages = collect($this->messages)
            ->filter(fn ($msg) => $msg->role !== Role::SYSTEM)
            ->toArray();

        $this->messages = [
            $systemMsg, ...$this->messages,
        ];

        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function addMessage(Message $msg): self
    {
        $this->messages[] = $msg;

        return $this;
    }

    public function addUserMessage(string $msg): self
    {
        $this->addMessage(new Message(Role::USER, $msg));

        return $this;
    }

    public function addAssistantMessage(string $msg): self
    {
        $this->addMessage(new Message(Role::ASSISTANT, $msg));

        return $this;
    }

    public function addMessageContent(string|Role $role, $content): self
    {
        $role = is_string($role) ? Role::from($role) : $role;
        $this->addMessage(new Message($role, $content));

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->messages);
    }

    public function replaceLastMessage(string $message, ?string $vision = null): self
    {
        $this->messages[count($this->messages) - 1] = new Message(Role::USER, $message, $vision);

        return $this;
    }

    public function get(): array
    {
        $system = $this->getSystem();

        $messages = collect($this->messages)
            ->filter(fn ($msg) => $msg->role !== Role::SYSTEM)
            ->take(is_null($system) ? $this->limit : $this->limit - 1);

        if ($system) {
            $messages->push($system);
        }

        return $messages->reverse()
            ->values()
            ->toArray();
    }

    public function jsonSerialize(): array
    {
        return collect($this->messages)
            ->map(fn ($item) => [$item->role->value => $item->message])
            ->toArray();
    }

    public function each(callable $callback): void
    {
        foreach ($this->messages as $key => $message) {
            $this->messages[$key] = $callback($message);
        }
    }

    public function getLatestMessageFrom(Role $role): string
    {
        return collect($this->messages)
            ->filter(fn ($msg) => $msg->role === $role)
            ->last()->message;
    }

    public function getLatestUserMessage(): string
    {
        return $this->getLatestMessageFrom(Role::USER);
    }

    public function addUserRequests(Collection $userRequests): self
    {
        foreach ($userRequests as $ur) {
            $this->addMessageContent(Role::USER, $ur->message);

            foreach ($ur->agentActions as $aa) {
                if ($aa->convoSummary && $aa->isSuccessful) {
                    $this->addMessageContent(Role::ASSISTANT, $aa->convoSummary);
                }
            }
        }

        return $this;
    }

    public function getSystem(): ?Message
    {
        return collect($this->messages)
            ->filter(fn ($msg) => $msg->role === Role::SYSTEM)
            ->first();
    }
}
