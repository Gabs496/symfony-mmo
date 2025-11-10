<?php

namespace App\GameObject\Render;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Render\Component\RenderComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Render:ItemRenderTemplate', template: 'components/Render/ItemRenderTemplate.html.twig')]
final class ItemRenderTemplate
{
    public GameObjectInterface|GameObjectPrototypeInterface $item;
    public bool $maskedDanger = false;
    #[ExposeInTemplate]
    public RenderComponent $render;
    public StackComponent $stack;
    public ?ItemEquipmentComponent $itemEquipment = null;
    public ?HealingComponent $healing = null;

    public function mount(GameObjectInterface|GameObjectPrototypeInterface $item, ?RenderComponent $render = null): void
    {
        $this->item = $item;
        $this->render = $render ?? $this->item->getComponent(RenderComponent::class);
        $this->stack = $this->item->getComponent(StackComponent::class);
        $this->itemEquipment = $this->item->getComponent(ItemEquipmentComponent::class);
        $this->healing = $this->item->getComponent(HealingComponent::class);
    }
}
