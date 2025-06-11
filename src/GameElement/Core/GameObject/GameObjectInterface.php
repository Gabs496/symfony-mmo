<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use Stringable;

interface GameObjectInterface extends GameComponentOwnerInterface, Stringable
{
    public function getId(): string;
    public function __toString(): string;
}