<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Core\GameComponent\GameComponentInterface;

class ItemEquipmentComponent implements GameComponentInterface
{
    protected ItemCondition $condition;

    public function __construct(
        /** @var array<AbstractStat> */
        private array $stats = [],
        float $maxCondition = 0.0,
    ) {
        $this->condition = new ItemCondition($maxCondition);
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }

    public function getCondition(): ItemCondition
    {
        return $this->condition;
    }

    public function setCondition(ItemCondition $condition): void
    {
        $this->condition = $condition;
    }

    public static function getId(): string
    {
        return 'item_equipment_component';
    }
}