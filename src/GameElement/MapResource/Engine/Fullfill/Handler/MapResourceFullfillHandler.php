<?php

namespace App\GameElement\MapResource\Engine\Fullfill\Handler;

use App\GameElement\MapResource\Engine\Fullfill\Event\MapResourceFullfill;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapResourceFullfillHandler
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function __invoke(MapResourceFullfill $message): void
    {
        $this->eventDispatcher->dispatch($message);
    }
}