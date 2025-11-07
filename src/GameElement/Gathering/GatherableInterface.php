<?php

namespace App\GameElement\Gathering;

use App\GameElement\Core\GameComponent\GameComponentInterface;

interface GatherableInterface
{
    /** @return GameComponentInterface[] */
    public function asGatherableComponents(): array;
}