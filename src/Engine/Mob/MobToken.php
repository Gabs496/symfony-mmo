<?php

namespace App\Engine\Mob;

use App\GameElement\Core\Token\TokenInterface;

class MobToken implements TokenInterface
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

    public function getExchangerClass(): string
    {
        return MobTokenExchanger::class;
    }
}