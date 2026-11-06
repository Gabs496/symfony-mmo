<?php

namespace App\GameElement\Map\EventListener;

use App\GameElement\Map\Component\InMapComponent;
use App\Stream\MapObjectStream;
use App\Stream\Streamer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use PennyPHP\Core\Event\GameObjectUpdateEvent;
use PennyPHP\Core\GameObjectInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Sends to the players looking at a map field a Turbo Stream with the new rendering of each game object that
 * changed in it. Changes are collected during the Doctrine flush and published once on postFlush, so an object
 * whose components change several times in the same flush is rendered and sent only once.
 *
 * - InMapComponent persisted / moved into a field   -> append
 * - InMapComponent removed / moved out of a field   -> remove
 * - any other component change of an object in a field -> update
 */
#[AsEntityListener(event: Events::postPersist, method: 'onInMapPersist', entity: InMapComponent::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'onInMapUpdate', entity: InMapComponent::class)]
#[AsEntityListener(event: Events::postRemove, method: 'onInMapRemove', entity: InMapComponent::class)]
#[AsDoctrineListener(event: Events::postFlush)]
class GameObjectListener
{
    private const string FIELD_PLACE = 'field';

    /** @var array<string, array{mapId: string, gameObject: GameObjectInterface, action: string}> */
    private array $pending = [];

    public function __construct(
        private readonly Streamer $streamer,
    )
    {
    }

    public function onInMapPersist(InMapComponent $inMap): void
    {
        if ($inMap->getPlace() === self::FIELD_PLACE) {
            $this->queue($inMap->getMapId(), $inMap->getGameObject(), MapObjectStream::APPEND);
        }
    }

    public function onInMapUpdate(InMapComponent $inMap, PreUpdateEventArgs $args): void
    {
        if (!$args->hasChangedField('mapId') && !$args->hasChangedField('place')) {
            return;
        }

        $oldMapId = $args->hasChangedField('mapId') ? $args->getOldValue('mapId') : $inMap->getMapId();
        $oldPlace = $args->hasChangedField('place') ? $args->getOldValue('place') : $inMap->getPlace();

        if ($oldPlace === self::FIELD_PLACE) {
            $this->queue($oldMapId, $inMap->getGameObject(), MapObjectStream::REMOVE);
        }
        if ($inMap->getPlace() === self::FIELD_PLACE) {
            $this->queue($inMap->getMapId(), $inMap->getGameObject(), MapObjectStream::APPEND);
        }
    }

    public function onInMapRemove(InMapComponent $inMap): void
    {
        if ($inMap->getPlace() === self::FIELD_PLACE) {
            $this->queue($inMap->getMapId(), $inMap->getGameObject(), MapObjectStream::REMOVE);
        }
    }

    #[AsEventListener(GameObjectUpdateEvent::class)]
    public function onGameObjectUpdate(GameObjectUpdateEvent $event): void
    {
        $inMap = $event->getGameObject()->getComponent(InMapComponent::class);
        if ($inMap instanceof InMapComponent && $inMap->getPlace() === self::FIELD_PLACE) {
            $this->queue($inMap->getMapId(), $event->getGameObject(), MapObjectStream::UPDATE);
        }
    }

    public function postFlush(): void
    {
        $pending = $this->pending;
        $this->pending = [];

        foreach ($pending as $item) {
            $this->streamer->send(new MapObjectStream($item['mapId'], $item['gameObject'], $item['action']));
        }
    }

    private function queue(string $mapId, GameObjectInterface $gameObject, string $action): void
    {
        $key = $mapId . '|' . $gameObject->getId();

        // "update" never overrides an append/remove already queued in the same flush:
        // an append already renders the latest state, and an object being removed must not be re-rendered.
        if ($action === MapObjectStream::UPDATE && isset($this->pending[$key])) {
            return;
        }

        $this->pending[$key] = ['mapId' => $mapId, 'gameObject' => $gameObject, 'action' => $action];
    }
}
