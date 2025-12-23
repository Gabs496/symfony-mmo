<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ItemEquipmentComponent implements GameComponentInterface
{
    public function __construct(
        /** @var array<AbstractStat> */
        private array $stats = [],
    )
    {
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }

    public static function getId(): string
    {
        return 'item_equipment_component';
    }
}