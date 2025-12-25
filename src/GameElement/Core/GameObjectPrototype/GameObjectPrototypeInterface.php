<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\Entity\Core\GameObject;
use App\GameElement\Core\GameObject\GameObjectInterface;

interface GameObjectPrototypeInterface extends GameObjectInterface
{
    public function make(): GameObject;
}