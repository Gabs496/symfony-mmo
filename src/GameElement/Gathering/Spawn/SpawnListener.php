<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SpawnListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            PreMapObjectSpawn::class => [
                ['onPreMapObjectSpawn', 0],
            ],
        ];
    }

    public function onPreMapObjectSpawn(PreMapObjectSpawn $event): void
    {
        $object = $event->getMapObject()->getGameObject();
        if (!$object->getComponent(GatheringComponent::class)) {
            return;
        }

        /** @var ResourceSpawn $spawnParams */
        $spawnParams = $event->getObjectSpawn();

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException) {
            $resourceQuantity = 1;
        }
        $object->setComponent(StackComponent::class, new StackComponent($resourceQuantity));
    }
}