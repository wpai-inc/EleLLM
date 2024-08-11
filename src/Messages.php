<?php

namespace WpAi\EleLLM;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use WpAi\EleLLM\Adapters\SimpleMessagesAdapter;
use WpAi\EleLLM\Enums\Role;
use WpAi\EleLLM\Interfaces\IMessagesAdapter;

class Messages implements Countable, IteratorAggregate, JsonSerializable
{
    private array $messages = [];

    private IMessagesAdapter $adapter;

    private int $limit = 10;

    public function __construct(?IMessagesAdapter $adapter = null)
    {
        $this->adapter = $adapter ?? new SimpleMessagesAdapter;
    }

    public function setAdapter(IMessagesAdapter $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function addMessage(Message $msg): self
    {
        if ($msg->isSystem()) {
            $nonSystemMessages = array_filter($this->messages, fn ($msg) => ! $msg->isSystem());
            $this->messages = [$msg, ...$nonSystemMessages];
        } else {
            $this->messages[] = $msg;
        }

        return $this;
    }

    public function addSystemMessage(string $msg)
    {
        $this->addMessage(new Message(Role::SYSTEM, $msg));

        return $this;
    }

    public function addUserMessage(string $msg, ?string $vision = null)
    {
        $this->addMessage(new Message(Role::USER, $msg, $vision));

        return $this;
    }

    public function addAssistantMessage(string $msg)
    {
        $this->addMessage(new Message(Role::ASSISTANT, $msg));

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->messages);
    }

    public function replaceLastMessage(Message $msg): self
    {
        $this->messages[count($this->messages) - 1] = $msg;

        return $this;
    }

    public function get(): array
    {
        $system = $this->getSystem();
        $limit = $system ? $this->limit - 1 : $this->limit;
        $messages = array_slice($this->filterRoles(Role::USER, Role::ASSISTANT), -$limit);

        $prefilteredMessages = [$system, ...$messages];

        return array_map(fn (Message $msg) => $this->adapter->clientMessage($msg), $prefilteredMessages);
    }

    public function jsonSerialize(): array
    {
        return $this->get();
    }

    public function each(callable $callback): void
    {
        $this->messages = array_map($callback, $this->messages);
    }

    public function getLatestMessageFrom(Role $role): ?string
    {
        $messages = $this->filterRoles($role);

        return $messages ? end($messages)->message : null;
    }

    public function getLatestUserMessage(): string
    {
        return $this->getLatestMessageFrom(Role::USER);
    }

    public function getSystem(): ?Message
    {
        return $this->messages[0]->isSystem() ? $this->messages[0] : null;
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->messages);
    }

    private function filterRoles(Role ...$roles): array
    {
        return array_filter($this->messages, fn ($msg) => in_array($msg->role, $roles));
    }
}
