<?php

namespace WpAi\EleLLM\Responses;

class ChatResponse
{
    public readonly ChatChoices $choices;

    public function __construct(
        array $choices,
        public ?Usage $usage = null,
        public readonly ?string $id = null,
        public readonly ?string $object = null,
        public readonly ?int $timestamp = null,
        public readonly ?string $model = null,
        public readonly ?string $systemFingerprint = null,
    ) {
        $this->choices = ChatChoices::create($choices);
    }

    public function __toString(): string
    {
        return $this->choices->getFirstChoiceContent();
    }

    public function setUsage(?int $promptTokens = null, ?int $completionTokens = null, ?int $totalTokens = null): self
    {
        if ($promptTokens && $completionTokens && $totalTokens) {
            $this->usage = new Usage(
                $promptTokens,
                $completionTokens,
                $totalTokens,
            );
        }

        return $this;
    }

    public function getFirstChoiceContent(): string
    {
        return $this->choices->getFirstChoiceContent();
    }
}
