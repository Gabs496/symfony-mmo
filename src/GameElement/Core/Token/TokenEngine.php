<?php

namespace App\GameElement\Core\Token;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\Repository\Data\PlayerCharacterRepository;
use App\Repository\Game\GameObjectRepository;

readonly class TokenEngine
{
    public function __construct(
        private GameObjectRepository      $gameObjectRepository,
        private PlayerCharacterRepository $playerCharacterRepository,
    ) {
    }

    public function exchange(string $token): GameObjectInterface
    {
        if($gameObject = $this->gameObjectRepository->find($token)) {
            return $gameObject;
        }

        return $this->playerCharacterRepository->find($token);
    }
}