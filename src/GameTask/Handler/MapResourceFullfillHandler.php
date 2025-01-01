<?php

namespace App\GameTask\Handler;

use App\GameEngine\Map\MapEngineCollection;
use App\GameTask\Message\MapResourceFullfill;
use App\Repository\Game\MapResourceRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class MapResourceFullfillHandler
{
    public function __construct(
        private MapResourceRepository $mapResourceRepository,
        private MapEngineCollection   $mapEngineCollection,
    )
    {
    }

    public function __invoke(MapResourceFullfill $message): void
    {
        $mapResource = $this->mapResourceRepository->find($message->getMapResourceId());
        $map = $this->mapEngineCollection->get($mapResource->getMapId());
        $map->resourceFullfill($mapResource);
    }
}