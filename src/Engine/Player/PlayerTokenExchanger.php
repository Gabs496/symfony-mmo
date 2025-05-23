<?php

namespace App\Engine\Player;

use App\GameElement\Core\Token\Event\TokenExpiredException;
use App\GameElement\Core\Token\TokenExchangerInterface;
use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;
use App\Repository\Data\PlayerCharacterRepository;

class PlayerTokenExchanger implements TokenExchangerInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
    )
    {

    }

    public function exchange(TokenInterface $token): TokenizableInterface
    {
        $player = $this->playerCharacterRepository->find($token->getId());
        if (!$player) {
            throw new TokenExpiredException();
        }
        return $player;
    }
}