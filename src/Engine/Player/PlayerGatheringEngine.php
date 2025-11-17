<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\GameObject;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
        private RewardEngine $rewardEngine,
    )
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceGatheredEvent::class => [
                ['pickResource', 0]
            ],
            ResourceGatheringEndedEvent::class => [
                ['continueUntillEmpty', 0],
            ],
        ];
    }

    public function pickResource(ResourceGatheredEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }

        /** @var GameObject $item */
        $item = $event->getItem();
        $this->rewardEngine->apply(new RewardApply(new ItemReward($item), $subject));
    }

    public function continueUntillEmpty(ResourceGatheringEndedEvent $event): void
    {
    }
}