<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;

interface GameObjectPrototypeInterface extends GameComponentOwnerInterface
{
    public function getId(): string;
}