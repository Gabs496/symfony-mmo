<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;

trait GameObjectTrait
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected string $id,
        /** @var GameComponentInterface[] */
        protected array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public  function __toString(): string
    {
        return $this::class . '::' . $this->getId();
    }
}