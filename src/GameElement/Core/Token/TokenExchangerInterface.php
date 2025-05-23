<?php

namespace App\GameElement\Core\Token;

interface TokenExchangerInterface
{
    public function exchange(TokenInterface $token): TokenizableInterface;
}