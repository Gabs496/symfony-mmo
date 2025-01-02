<?php

namespace App\GameEngine\Reward;

use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Reward\RewardPlayer;
use App\GameEngine\Item\ItemCollection;
use App\GameEngine\Player\PlayerEngine;
use App\GameObject\Reward\ItemReward;
use App\GameObject\Reward\MasteryReward;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardPlayerHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private ItemCollection            $itemCollection,
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
           $item = $this->itemCollection->get($reward->getItemId());
           $this->playerEngine->giveItem($playerCharacter, ItemInstance::createFrom($item, $reward->getQuantity()));
       }

        $this->repository->save($playerCharacter);
    }
}