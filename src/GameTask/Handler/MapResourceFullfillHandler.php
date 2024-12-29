<?php

namespace App\GameTask\Handler;

use App\GameRule\Map;
use App\GameTask\Message\MapResourceFullfill;
use App\Repository\Game\MapResourceRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapResourceFullfillHandler
{
    public function __construct(
        private MapResourceRepository $mapResourceRepository,
        private Map                   $gameMap,
    )
    {
    }

    public function __invoke(MapResourceFullfill $message): void
    {
        $mapResource = $this->mapResourceRepository->find($message->getMapResourceId());
        $this->gameMap->mapResourceFullfill($mapResource);
    }
}