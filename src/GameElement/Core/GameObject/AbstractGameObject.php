<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;

abstract class AbstractGameObject implements GameObjectInterface
{
    use GameComponentOwnerTrait;

    public function __construct(
        protected string $id,
        /** @var GameComponentInterface[] */
        private array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}