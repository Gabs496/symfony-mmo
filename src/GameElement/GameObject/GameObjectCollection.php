<?php

namespace App\GameElement\GameObject;

use Attribute;

#[Attribute]
class GameObjectCollection
{
    public function __construct(
        private string $id,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}