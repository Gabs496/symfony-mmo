<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;

interface GameObjectPrototypeInterface extends GameComponentOwnerInterface
{
    public function getId(): string;
}