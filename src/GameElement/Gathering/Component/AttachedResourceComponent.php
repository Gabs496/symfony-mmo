<?php

namespace App\GameElement\Gathering\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AttachedResourceComponent implements GameComponentInterface
{
    public function __construct(
        private readonly int  $maxAvaliability = 1,
        private int $availability = 1
    ){

    }

    public function getMaxAvaliability(): int
    {
        return $this->maxAvaliability;
    }

    public function getAvailability(): int
    {
        return $this->availability;
    }

    public function decreaseAvailability(int $amount = 1): void
    {
        $this->availability -= $amount;
    }

    public static function getId(): string
    {
        return "attached_resource_component";
    }
}