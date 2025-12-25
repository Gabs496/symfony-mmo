<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameObject\GameObjectInterface;

interface GameObjectPrototypeInterface extends GameObjectInterface
{
    public function make(): GameObjectInterface;
}