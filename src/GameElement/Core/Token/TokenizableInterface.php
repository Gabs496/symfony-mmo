<?php

namespace App\GameElement\Core\Token;

interface TokenizableInterface
{
    public function getToken(): TokenInterface;
}