<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\AbstractGameComponent;
use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;

readonly abstract class AbstractGameObject implements GameObjectInterface, GameComponentOwnerInterface
{
    use GameComponentOwnerTrait;

    public function __construct(
        protected string $id,
        /** @var AbstractGameComponent */
        protected array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}