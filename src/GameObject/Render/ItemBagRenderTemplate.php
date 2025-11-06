<?php

namespace App\GameObject\Render;

use App\Entity\Data\BackpackItemBag;
use App\Entity\Data\EquippedItemBag;
use App\Entity\Data\ItemObject;
use App\Entity\Game\GameObject;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Interaction\InteractableTemplateInterface;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Item\Interaction\DropInteraction;
use App\GameElement\Item\Interaction\EatInteraction;
use App\GameElement\Item\Render\ItemBagRenderComponent;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\ItemEquiment\Interaction\EquipInteraction;
use App\GameElement\ItemEquiment\Interaction\UnequipInteraction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "Render:ItemBagRenderTemplate", template: 'components/Render/ItemBagRenderTemplate.html.twig')]
class ItemBagRenderTemplate implements InteractableTemplateInterface
{
    #[ExposeInTemplate]
    public ItemObject $itemObject;

    public GameObject $item;

    public bool $maskedDanger = false;

    #[ExposeInTemplate]
    public ItemBagRenderComponent $render;

    public StackComponent $stack;

    public ?ItemEquipmentComponent $itemEquipment = null;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function mount(ItemObject $itemObject): void
    {
        $this->itemObject = $itemObject;
        $this->item = $itemObject->getGameObject();
        $this->render = $this->item->getComponent(ItemBagRenderComponent::class);
        $this->stack = $this->item->getComponent(StackComponent::class);
        $this->itemEquipment = $this->item->getComponent(ItemEquipmentComponent::class);
    }

    public function getInteractions(): iterable
    {
        if ($this->item->hasComponent(HealingComponent::class)) {
            yield new EatInteraction($this->urlGenerator->generate('app_item_eat', ['id' => $this->itemObject->getId()]));
        }

        if ($this->item->hasComponent(ItemEquipmentComponent::class)) {
            if ($this->itemObject->getBag() instanceof BackpackItemBag) {
                yield new EquipInteraction($this->urlGenerator->generate('app_item_equip', ['id' => $this->itemObject->getId()]));
            }
            if ($this->itemObject->getBag() instanceof EquippedItemBag) {
                yield new UnequipInteraction($this->urlGenerator->generate('app_item_unequip', ['id' => $this->itemObject->getId()]));
            }
        }

        yield new DropInteraction($this->urlGenerator->generate('app_item_drop', ['id' => $this->itemObject->getId()]));
    }
}