<?php

namespace App\GameTask\Handler;

use App\GameRule\GameMap;
use App\GameTask\Message\MapResourceFullfill;
use App\Repository\Game\MapResourceRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapResourceFullfillHandler
{
    public function __construct(
        private MapResourceRepository $mapResourceRepository,
        private GameMap $gameMap,
    )
    {
    }

    public function __invoke(MapResourceFullfill $message): void
    {
        $mapResource = $this->mapResourceRepository->find($message->getMapResourceId());
        $this->gameMap->mapResourceFullfill($mapResource);
    }
}