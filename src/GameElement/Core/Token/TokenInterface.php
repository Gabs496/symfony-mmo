<?php

namespace App\GameElement\Core\Token;

interface TokenInterface
{
    public function getId(): string;

    public function getExchangerClass(): string;
}