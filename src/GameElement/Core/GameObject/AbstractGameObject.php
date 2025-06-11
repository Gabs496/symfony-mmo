<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;

abstract class AbstractGameObject implements GameObjectInterface
{
    use GameObjectTrait;
}