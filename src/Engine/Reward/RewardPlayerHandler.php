<?php

namespace App\Engine\Reward;

use App\Engine\Player\PlayerEngine;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Crafting\Reward\ItemReward;
use App\GameElement\Reward\RewardPlayer;
use App\GameObject\Reward\MasteryReward;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardPlayerHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerEngine              $playerEngine,
    )
    {
    }

    public function __invoke(RewardPlayer $rewardPlayer): void
    {
        $playerCharacter = $this->repository->find($rewardPlayer->getPlayerId());
        if (!$playerCharacter instanceof PlayerCharacter) {
            //TODO: scrivere un log o eseguire qualcosa
            return;
        }

        $reward = $rewardPlayer->getReward();
        if ($reward instanceof MasteryReward) {
            $playerCharacter->increaseMasteryExperience($reward->getType(), $reward->getExperience());
        }

       if ($reward instanceof ItemReward) {
           $this->playerEngine->giveItem($playerCharacter, ItemInstance::createFrom($reward->getItem(), $reward->getQuantity()));
       }

        $this->repository->save($playerCharacter);
    }
}