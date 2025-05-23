<?php

namespace App\Engine\Player;

use App\GameElement\Core\Token\TokenInterface;

readonly class PlayerToken implements TokenInterface
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

    public function getExchangerClass(): string
    {
        return PlayerTokenExchanger::class;
    }
}
