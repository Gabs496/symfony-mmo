<?php

namespace App\GameElement\Interaction;

abstract class AbstractInteraction
{
    public function __construct(
        protected string $label,
        protected string $action,
    ) {

    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}