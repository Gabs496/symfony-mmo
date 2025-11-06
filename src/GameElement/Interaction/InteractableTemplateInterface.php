<?php

namespace App\GameElement\Interaction;

interface InteractableTemplateInterface
{
    /** @return iterable<AbstractInteraction> */
    public function getInteractions(): iterable;
}