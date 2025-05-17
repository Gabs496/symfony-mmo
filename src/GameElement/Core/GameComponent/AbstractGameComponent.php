<?php

namespace App\GameElement\Core\GameComponent;

use App\GameElement\Core\GameObject\GameObjectInterface;

abstract class AbstractGameComponent
{
    public function __construct(
        protected ?GameObjectInterface $gameObject = null,
    ){}

    public function getGameObject(): ?GameObjectInterface
    {
        return $this->gameObject;
    }
}