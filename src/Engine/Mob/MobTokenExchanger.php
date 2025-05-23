<?php

namespace App\Engine\Mob;

use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Core\Token\Event\TokenExpiredException;
use App\GameElement\Core\Token\TokenExchangerInterface;
use App\GameElement\Core\Token\TokenInterface;
use App\Repository\Game\MapSpawnedMobRepository;

readonly class MobTokenExchanger implements TokenExchangerInterface
{
    public function __construct(
        protected MapSpawnedMobRepository $mobRepository,
    )
    {

    }

    public function exchange(TokenInterface $token): MapSpawnedMob
    {
        $mob = $this->mobRepository->find($token->getId());
        if (!$mob) {
            throw new TokenExpiredException();
        }
        return $mob;
    }
}