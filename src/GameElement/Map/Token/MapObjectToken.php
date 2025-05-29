<?php

namespace App\GameElement\Map\Token;

use App\GameElement\Core\Token\TokenInterface;

readonly class MapObjectToken implements TokenInterface
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
        return MapObjectTokenExchanger::class;
    }
}