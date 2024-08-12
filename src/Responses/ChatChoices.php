<?php

namespace WpAi\EleLLM\Responses;

class ChatChoices
{
    public array $choices = [];

    public function addChoice(ChatChoice $choice): void
    {
        $this->choices[$choice->index] = $choice;
    }

    public function append(ChatChoice $choice): void
    {
        if (! isset($this->choices[$choice->index])) {
            $this->choices[$choice->index] = $choice;
        } else {
            $prevChoice = $this->choices[$choice->index];
            $this->choices[$choice->index] = new ChatChoice(
                index: $choice->index,
                message: new ChatMessage(
                    $prevChoice->message->role.$choice->message->role,
                    $prevChoice->message->content.$choice->message->content
                ),
                finishReason: $choice->finishReason
            );
        }
    }

    public static function create(array $choices): self
    {
        $self = new self;
        foreach ($choices as $choice) {
            $self->addChoice($choice);
        }

        return $self;
    }

    public function getFirstChoiceContent(): string
    {
        if (! isset($this->choices[0])) {
            return '';
        }

        return $this->choices[0]->getContent() ?? '';
    }
}
