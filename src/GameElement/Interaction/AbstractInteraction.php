<?php

namespace App\GameElement\Interaction;

abstract class AbstractInteraction
{
    public function __construct(
        protected string $label,
        protected Action $action,
    ) {

    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getAction(): Action
    {
        return $this->action;
    }
}