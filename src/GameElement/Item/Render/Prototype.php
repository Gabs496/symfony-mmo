<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Item\AbstractItemPrototype;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "Item:Prototype")]
class Prototype
{
    #[ExposeInTemplate]
    protected AbstractItemPrototype $item;

    #[ExposeInTemplate]
    protected float $quantity = 1.0;

    #[ExposeInTemplate]
    protected $maskedDanger = false;

    public function __construct(
        protected GameObjectEngine $gameObjectEngine
    )
    {
    }

    public function mount(string $id): void
    {
        /** @var AbstractItemPrototype $item */
        $item = $this->gameObjectEngine->get($id);
        $this->item = $item;
    }

    public function getItem(): AbstractItemPrototype
    {
        return $this->item;
    }

    public function setItem(AbstractItemPrototype $item): void
    {
        $this->item = $item;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function isMaskedDanger(): bool
    {
        return $this->maskedDanger;
    }

    public function setMaskedDanger(bool $maskedDanger): void
    {
        $this->maskedDanger = $maskedDanger;
    }
}