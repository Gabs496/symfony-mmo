<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\Entity\Core\GameObject;
use App\GameElement\Core\GameComponent\GameComponentInterface;

interface GameObjectPrototypeInterface
{
    public static function getId(): string;

    /** @return GameComponentInterface[] */
    public function getComponents(): array;

    public function make(): GameObject;
}