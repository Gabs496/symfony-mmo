<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Gathering\Component\AttachedResourceComponent;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Map\Event\PreMapObjectSpawnEvent;
use Exception;
use Random\RandomException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class SpawnListener
{
    #[AsEventListener(PreMapObjectSpawnEvent::class)]
    public function onPreMapObjectSpawn(PreMapObjectSpawnEvent $event): void
    {
        $object = $event->getObject();

        $spawnParams = $event->getObjectSpawn();
        if (!$spawnParams instanceof ResourceSpawn) {
            return;
        }

        if (!$object->hasComponent(ResourceComponent::class)) {
            throw new Exception();
        }

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException) {
            $resourceQuantity = 1;
        }
        $object->setComponent(new AttachedResourceComponent($resourceQuantity, $resourceQuantity));
    }

}