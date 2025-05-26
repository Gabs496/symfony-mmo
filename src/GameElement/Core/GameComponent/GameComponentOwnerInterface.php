<?php

namespace App\GameElement\Core\GameComponent;

interface GameComponentOwnerInterface
{
    /** @return GameComponentInterface[] */
    public function getComponents(): array;
}