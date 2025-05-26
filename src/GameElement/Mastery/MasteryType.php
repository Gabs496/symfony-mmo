<?php

namespace App\GameElement\Mastery;

use App\GameElement\Core\GameComponent\GameComponentInterface;

abstract readonly class MasteryType implements GameComponentInterface
{
    public function __construct(
        protected string $id,
    )
    {
    }
    public function getId(): string
    {
        return $this->id;
    }

    public static abstract function getName(): string;
    public function __toString(): string
    {
        return static::getId();
    }
}
