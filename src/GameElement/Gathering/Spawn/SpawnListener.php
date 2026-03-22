<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\Component\ResourceStatus;
use App\GameElement\Map\Event\PreSpawnEvent;
use PennyPHP\Core\Exception\GameComponentRequiredException;
use Random\RandomException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class SpawnListener
{
    /**
     * @throws GameComponentRequiredException
     */
    #[AsEventListener(PreSpawnEvent::class)]
    public function onPreSpawn(PreSpawnEvent $event): void
    {
        $object = $event->getObject();

        $spawnParams = $event->getObjectSpawn();
        if (!$spawnParams instanceof ResourceSpawn) {
            return;
        }

        if (!$object->hasComponent(ResourceComponent::class)) {
            throw new GameComponentRequiredException(ResourceComponent::class, $object);
        }

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException) {
            $resourceQuantity = 1;
        }
        $object->setComponent(new ResourceStatus($resourceQuantity, $resourceQuantity));
    }

}