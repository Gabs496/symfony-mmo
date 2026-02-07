<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Core\GameObject\GameObjectInterface;

interface GameObjectPrototypeInterface extends GameObjectInterface
{
    public function make(): GameObject;

    public static function getType(): string;
}