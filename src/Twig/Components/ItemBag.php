<?php

namespace App\Twig\Components;

use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\ItemBagEngine;
use PennyPHP\Core\Entity\GameObject;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ItemBag
{
    public GameObject $player;
    public ItemBagComponent $bag;

    public function __construct(
        private ItemBagEngine $bagEngine
    )
    {}

    public function mount(GameObject $player): void
    {
        $this->player = $player;
        $this->bag = $player->getComponent(ItemBagComponent::class);
    }

    public function getBagEngine(): ItemBagEngine
    {
        return $this->bagEngine;
    }

    public function getFullness(): float
    {
        return $this->bagEngine->getFullness($this->bag);
    }
}
