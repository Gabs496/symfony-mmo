<?php

namespace App\Engine\Player;

use App\Entity\Data\Player;
use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Reward\Engine\RewardEngine;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
        private RewardEngine $rewardEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
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
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $subject]);
        if (!$player instanceof Player) {
            return;
        }

        /** @var GameObject $item */
        $item = $event->getItem();
        $this->rewardEngine->apply(new ItemReward($item), $subject);
    }

    public function continueUntillEmpty(ResourceGatheringEndedEvent $event): void
    {
    }
}