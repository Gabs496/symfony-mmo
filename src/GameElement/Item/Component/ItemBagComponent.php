<?php

namespace App\GameElement\Item\Component;

use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemBagComponent extends GameComponent
{
    public function __construct(
        #[Column]
        protected float $maxSize,

    )
    {
        parent::__construct();
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }
}