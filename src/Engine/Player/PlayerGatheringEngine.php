<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\GameObject;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
        private PlayerItemEngine $playerItemEngine,
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
        $this->playerItemEngine->giveItem($subject, $item);
    }

    public function continueUntillEmpty(ResourceGatheringEndedEvent $event): void
    {
    }
}