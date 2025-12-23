<?php

namespace App\GameElement\Character;

/** @deprecated */
abstract class AbstractCharacter
{
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}