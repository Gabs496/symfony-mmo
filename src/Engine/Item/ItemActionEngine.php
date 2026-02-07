<?php

namespace App\Engine\Item;

use App\Engine\Math;
use App\Entity\Data\Player;
use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Healing\Engine\HealingEngine;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;

readonly class ItemActionEngine
{
    public function __construct(
        private HealingEngine             $healingEngine,
        private NotificationEngine        $notificationEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
    )
    {
    }

    public function eat(Player $player, GameObject $item): void
    {
        if ($healing = $item->getComponent(HealingComponent::class)) {
            $this->healingEngine->heal($player->getGameObject(), $healing);
            if ($healing->getAmount() > 0.0) {
                $this->notificationEngine->success(
                    $player->getId(),
                    sprintf('+%s health restored.', Math::getStatViewValue($healing->getAmount()))
                );
            } else {
                $this->notificationEngine->danger(
                    $player->getId(),
                    sprintf('%s health lost.', Math::getStatViewValue(abs($healing->getAmount())))
                );
            }
        }
        $this->playerCharacterRepository->save($player);
    }
}