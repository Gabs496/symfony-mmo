<?php

namespace App\GameElement\Map\Token;

use App\Entity\Game\MapObject;
use App\GameElement\Core\Token\Event\TokenExpiredException;
use App\GameElement\Core\Token\TokenExchangerInterface;
use App\GameElement\Core\Token\TokenInterface;
use App\Repository\Game\MapObjectRepository;

readonly class MapObjectTokenExchanger implements TokenExchangerInterface
{
    public function __construct(
        protected MapObjectRepository $mapObjectRepository,
    )
    {

    }

    public function exchange(TokenInterface $token): MapObject
    {
        $object = $this->mapObjectRepository->find($token->getId());
        if (!$object) {
            throw new TokenExpiredException();
        }
        return $object;
    }
}