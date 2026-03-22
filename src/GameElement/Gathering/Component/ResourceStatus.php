<?php

namespace App\GameElement\Gathering\Component;

use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
#[Table(name: "resource_attached_component")]
class ResourceStatus extends GameComponent
{
    public function __construct(
        #[Column]
        private readonly int  $maxAvaliability = 1,
        #[Column]
        private int $availability = 1
    ){
        parent::__construct();
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