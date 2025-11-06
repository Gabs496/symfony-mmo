<?php

namespace App\GameElement\Map\Component\Spawn;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class SpawnComponent implements GameComponentInterface
{
    public function __construct(
        /** @var ObjectSpawn[] */
        protected array $spawns,
    )
    {
    }

    public function getSpawns(): array
    {
        return $this->spawns;
    }

    public function setSpawns(array $spawns): void
    {
        $this->spawns = $spawns;
    }

    public static function getId(): string
    {
        return 'spawn_component';
    }
}