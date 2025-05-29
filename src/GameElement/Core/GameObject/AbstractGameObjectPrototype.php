<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentInterface;

abstract class AbstractGameObjectPrototype implements GameObjectPrototypeInterface
{
    use GameObjectPrototypeTrait;

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