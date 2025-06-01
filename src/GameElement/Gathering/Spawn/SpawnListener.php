<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Gathering\Component\Gathering;
use App\GameElement\Health\Component\Health;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SpawnListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            PreMapObjectSpawn::class => [
                ['onPreMapObjectSpawn', 0],
            ],
        ];
    }

    public function onPreMapObjectSpawn(PreMapObjectSpawn $event): void
    {
        $object = $event->getObject();
        if (!$object->getComponent(Gathering::class)) {
            return;
        }

        /** @var ResourceSpawn $spawnParams */
        $spawnParams = $event->getObjectSpawn();

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException $e) {
            $resourceQuantity = 1;
        }
        $object->setComponent(Health::class, new Health($resourceQuantity, $resourceQuantity));
    }
}