<?php

namespace App\GameElement\Equipment\Component;

use App\GameElement\Equipment\EquipmentSet\BaseEquipmentSlotTypes;
use App\GameElement\Equipment\EquipmentSlotSetInterface;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class EquipmentSetComponent extends GameComponent
{
    /** @var array<string> */
    #[Column]
    protected array $slots;
    public function __construct(
        EquipmentSlotSetInterface|array $slots,
    )
    {
        parent::__construct();
        $this->slots = is_array($slots) ? $slots : $slots->getSlots();
    }

    /** @return array<string */
    public function getSlots(): array
    {
        return $this->slots;
    }

    public function hasSlot(string $id): bool
    {
        return in_array($id, $this->slots, true);
    }
}