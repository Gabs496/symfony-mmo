<?php

namespace App\Engine\Player;

use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
    )
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceGatheringEndedEvent::class => [
                ['continueUntillEmpty', 0],
            ],
        ];
    }

    public function continueUntillEmpty(ResourceGatheringEndedEvent $event): void
    {
    }
}