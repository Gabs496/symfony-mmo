<?php

namespace App\Twig\Components\Render;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Interaction\Action;
use App\GameElement\Interaction\InteractableTemplateInterface;
use App\GameElement\Item\Interaction\DropInteraction;
use App\GameElement\Item\Interaction\EatInteraction;
use App\GameElement\Equipment\Component\EquipmentComponent;
use App\GameElement\Equipment\Interaction\UnequipInteraction;
use App\GameElement\Render\Component\RenderComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: "Render:ItemBagRenderTemplate", template: 'components/Render/ItemBagRenderTemplate.html.twig')]
final class ItemBagRenderTemplate implements InteractableTemplateInterface
{
    public GameObject $item;
    public RenderComponent $render;

    public function mount(GameObject $item): void
    {
        $this->item = $item;
        $this->render = $item->getComponent(RenderComponent::class);
    }

    public function getInteractions(): iterable
    {
        if ($this->item->hasComponent(HealingComponent::class)) {
            yield new EatInteraction(new Action('app_item_eat', ['id' => $this->item->getId()]));
        }

        if ($this->item->hasComponent(EquipmentComponent::class)) {
            yield new UnequipInteraction(new Action('app_item_equip', ['id' => $this->item->getId()]));
        }

        yield new DropInteraction(new Action('app_item_drop', ['id' => $this->item->getId()]));
    }
}