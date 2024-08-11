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
            $self->addChoice($choice);
        }

        return $self;
    }

    public function getFirstChoiceContent(): string
    {
        return $this->choices[0]->getContent();
    }
}
