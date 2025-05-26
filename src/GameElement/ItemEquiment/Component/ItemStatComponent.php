<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class ItemStatComponent implements GameComponentInterface
{
    public function __construct(
        protected array $stats,
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
}