<?php

namespace App\GameElement\MapResource\Engine\Spawn\Handler;

use App\GameElement\MapResource\Engine\Spawn\Event\MapResourceSpawnAction;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapResourceSpawnHandler
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function __invoke(MapResourceSpawnAction $message): void
    {
        $this->eventDispatcher->dispatch($message);
    }
}