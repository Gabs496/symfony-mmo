<?php

namespace App\GameElement\Healing\Component;

use PennyPHP\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class HealingComponent extends GameComponent
{
    public function __construct(
        #[Column]
        protected float $amount,
    ) {
        parent::__construct();
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public static function getComponentName(): string
    {
        return 'healing_component';
    }
}