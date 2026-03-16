<?php

namespace App\GameElement\Item\Component;

use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemComponent extends GameComponent
{
    public function __construct(
        #[Column]
        private float                   $weight = 0.0,
    )
    {
        parent::__construct();
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public static function getComponentName(): string
    {
        return 'item_weight_component';
    }
}