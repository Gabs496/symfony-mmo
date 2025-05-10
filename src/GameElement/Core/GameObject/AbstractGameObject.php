<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;

readonly abstract class AbstractGameObject implements GameComponentOwnerInterface
{
    use GameComponentOwnerTrait;

    public function __construct(
        protected string $id,
        protected array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}