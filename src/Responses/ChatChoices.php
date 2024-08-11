<?php

namespace WpAi\EleLLM\Responses;

class ChatChoices
{
    public array $choices;

    public function addChoice(ChatChoice $choice): void
    {
        $this->choices[] = $choice;
    }

    public static function create(array $choices): self
    {
        $self = new self;
        foreach ($choices as $choice) {
            $self->addChoice(new ChatChoice(
                $choice->index,
                new ChatMessage(
                    $choice->message->role,
                    $choice->message->content
                ),
                $choice?->finishReason,
            ));
        }

        return $self;
    }

    public function getFirstChoiceContent(): string
    {
        return $this->choices[0]->getContent();
    }
}
