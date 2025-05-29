<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Render\Component\Render;
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

    #[ExposeInTemplate]
    protected Render $render;

    public function __construct(
        protected GameObjectEngine $gameObjectEngine
    )
    {
    }

    public function mount(string $id): void
    {
        /** @var AbstractItemPrototype $item */
        $item = $this->gameObjectEngine->getPrototype($id);
        $this->item = $item;
        $this->render = $item->getComponent(Render::class);
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

    public function getRender(): Render
    {
        return $this->render;
    }

    public function setRender(Render $render): void
    {
        $this->render = $render;
    }
}