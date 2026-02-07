<?php

namespace App\GameElement\Gathering\Component;

use PennyPHP\Core\GameComponent\Entity\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ResourceComponent extends GameComponent
{
    public function __construct(
        #[Column]
        private readonly float  $gatheringDifficulty,
        #[Column]
        private readonly string $involvedMastery,
    ){
        parent::__construct();
    }

    public function getGatheringDifficulty(): float
    {
        return $this->gatheringDifficulty;
    }

    public function getInvolvedMastery(): string
    {
        return $this->involvedMastery;
    }

    public static function getComponentName(): string
    {
        return "resource_component";
    }
}