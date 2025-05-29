<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;

interface GameObjectInterface extends GameComponentOwnerInterface
{
    public function getId(): string;
}