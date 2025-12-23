<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Gathering\Component\AttachedResourceComponent;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class SpawnListener implements EventSubscriberInterface
{

     /** @return array<string, array<int, array{0: string, 1: int}>> */

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

        if (!$object->hasComponent(ResourceComponent::class)) {
            return;
        }

        /** @var ResourceSpawn $spawnParams */
        $spawnParams = $event->getObjectSpawn();

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException) {
            $resourceQuantity = 1;
        }
        $itemComponent = $object->getComponent(ItemComponent::class);
        $itemComponent->setQuantity($resourceQuantity);
        $object->setComponent(new AttachedResourceComponent($resourceQuantity, $resourceQuantity));
    }

}