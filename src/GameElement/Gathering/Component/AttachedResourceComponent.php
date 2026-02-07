<?php

namespace App\GameElement\Gathering\Component;

use PennyPHP\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class AttachedResourceComponent extends GameComponent
{
    public function __construct(
        #[Column]
        private readonly int  $maxAvaliability = 1,
        #[Column]
        private int $availability = 1
    ){

    }

    public function getMaxAvaliability(): int
    {
        return $this->maxAvaliability;
    }

    public function getAvailability(): int
    {
        return $this->availability;
    }

    public function decreaseAvailability(int $amount = 1): void
    {
        $this->availability -= $amount;
    }

    public static function getComponentName(): string
    {
        return "attached_resource_component";
    }
}