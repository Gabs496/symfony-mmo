<?php

namespace App\GameElement\Equipment\Component;

use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class EquipmentSetComponent extends GameComponent
{
    public function __construct(
        /** @var array<string> */
        #[Column]
        protected array $slots,
    )
    {
        parent::__construct();
    }

    public function getSlots(): array
    {
        return $this->slots;
    }

    public function hasSlot(string $id): bool
    {
        return in_array($id, $this->slots, true);
    }
}