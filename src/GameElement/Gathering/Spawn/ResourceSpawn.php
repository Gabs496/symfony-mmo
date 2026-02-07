<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "map_resource_spawn")]
class ResourceSpawn extends ObjectSpawn
{
    public function __construct(
        string         $prototypeId,
        int            $maxAvailability,
        float          $spawnRate,
        #[Column]
        protected int  $minSpotAvailability,
        #[Column]
        protected  int $maxSpotAvailability,
    )
    {
        parent::__construct($prototypeId, $maxAvailability, $spawnRate);
    }

    public function getMinSpotAvailability(): int
    {
        return $this->minSpotAvailability;
    }

    public function getMaxSpotAvailability(): int
    {
        return $this->maxSpotAvailability;
    }
}