<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameObject\GameObjectInterface;

interface GameObjectPrototypeInterface
{
    public static function getId(): string;

    /** @param GameObjectInterface[] $components */
    public function make(array $components = []);
}