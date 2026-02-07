<?php

namespace App\GameElement\Item\Component;

use PennyPHP\Core\GameComponent\Entity\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class ItemBagComponent extends GameComponent
{
    public function __construct(
        #[Column]
        protected float $size
    )
    {
        parent::__construct();
    }

    public function getSize(): int
    {
        return $this->size;
    }
}