<?php

namespace App\GameElement\Equipment\Component;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class EquipmentComponent extends GameComponent
{
    public function __construct(
        private readonly string $targetSlot,
        /** @var array<AbstractStat> */
        #[Column]
        private array           $stats = [],
    )
    {
        parent::__construct();
    }

    public function getTargetSlot(): string
    {
        return $this->targetSlot;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }

    public static function getComponentName(): string
    {
        return 'item_equipment_component';
    }
}