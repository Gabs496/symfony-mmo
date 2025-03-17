<?php

namespace App\GameElement\MapMob\Engine\Fullfill\Handler;

use App\GameElement\MapMob\Engine\Fullfill\Event\MapMobFullfill;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapMobFullfillHandler
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function __invoke(MapMobFullfill $message): void
    {
        $this->eventDispatcher->dispatch($message);
    }
}