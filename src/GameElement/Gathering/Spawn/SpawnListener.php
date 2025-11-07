<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Gathering\GatherableInterface;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use InvalidArgumentException;
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
        $prototype = $object->getPrototype();

        if (!$prototype instanceof GatherableInterface) {
            return;
        }

        if ($object->hasComponent(GatheringComponent::class)) {
            return;
        }

        $asGatherableComponents = $prototype->asGatherableComponents();
        $this->assertContainsAtLeastOneGatheringComponent($prototype, $asGatherableComponents);

        foreach ($asGatherableComponents as $gatherableComponent) {
            $object->setComponent($gatherableComponent);
        }

        /** @var ResourceSpawn $spawnParams */
        $spawnParams = $event->getObjectSpawn();

        try {
            $resourceQuantity = random_int($spawnParams->getMinSpotAvailability(), $spawnParams->getMaxSpotAvailability());
        } catch (RandomException) {
            $resourceQuantity = 1;
        }
        $object->setComponent(new StackComponent($resourceQuantity));
    }

    /** @param GameComponentInterface[] $components */
    private function assertContainsAtLeastOneGatheringComponent(GameObjectPrototypeInterface $prototype, array $components): void
    {
        $gatheringComponent = null;

        foreach ($components as $component) {
            if ($component instanceof GatheringComponent) {
                $gatheringComponent = $component;
                break;
            }
        }

        if (!$gatheringComponent) {
            throw new InvalidArgumentException(sprintf("Function %s::asGatherable() must return at last one component of class %s at key %s", $prototype::class, GatheringComponent::class, GatheringComponent::class));
        }
    }
}