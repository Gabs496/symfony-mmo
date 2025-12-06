<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use Stringable;

interface GameObjectInterface extends GameComponentOwnerInterface, Stringable
{
    public function getId(): string;

    public function clone(): GameObjectInterface;

    public function __toString(): string;
}