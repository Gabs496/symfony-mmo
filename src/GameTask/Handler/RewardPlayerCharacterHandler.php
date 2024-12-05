<?php

namespace App\GameTask\Handler;

use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\Item;
use App\GameTask\Message\RewardItem;
use App\GameTask\Message\RewardMastery;
use App\GameTask\Message\RewardPlayerCharacterInterface;
use App\Repository\Data\PlayerCharacterRepository;
use App\Repository\Game\ItemRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardPlayerCharacterHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private ItemRepository            $itemRepository,
    )
    {
    }

    public function __invoke(RewardPlayerCharacterInterface $reward): void
    {
        $playerCharacter = $this->repository->find($reward->getPlayerCharacterId());
        if (!$playerCharacter instanceof PlayerCharacter) {
            //TODO: scrivere un log o eseguire qualcosa
            return;
        }

        if ($reward instanceof RewardMastery) {
            $playerCharacter->increaseMasteryExperience($reward->getType(), $reward->getExperience());
        }

       if ($reward instanceof RewardItem) {
           $item = $this->itemRepository->find($reward->getItemId());
           if (!$item instanceof Item) {
               //TODO: scrivere un log o eseguire qualcosa
               return;
           }

           $playerCharacter->takeItem(ItemInstance::createFrom($item, $reward->getQuantity()));
       }

        $this->repository->save($playerCharacter);
    }
}