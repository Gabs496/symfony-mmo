<?php

namespace App\GameElement\MapMob\Engine\Spawn\Handler;

use App\GameElement\MapMob\Engine\Spawn\Event\MapMobSpawnAction;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapMobSpawnHandler
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function __invoke(MapMobSpawnAction $message): void
    {
        $this->eventDispatcher->dispatch($message);
    }
}