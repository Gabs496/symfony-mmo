<?php

namespace App\Twig\Components\Render;

use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Interaction\FightInteraction;
use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Gathering\Component\AttachedResourceComponent;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\Interaction\GatherInteraction;
use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;
use App\GameElement\Interaction\InteractableTemplateInterface;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'Render:MapRenderTemplate', template: 'components/Render/MapRenderTemplate.html.twig')]
class MapRenderTemplate implements InteractableTemplateInterface
{

    public GameObject $gameObject;

    public RenderComponent $render;
    public ?CharacterComponent $characterComponent;

    public ?ItemComponent $itemComponent = null;

    public ?ResourceComponent $gatheringComponent = null;

    public ?AttachedResourceComponent $attachedResourceComponent = null;
    public function mount(GameObject $gameObject): void
    {
        $this->gameObject = $gameObject;
        $this->render = $this->gameObject->getComponent(RenderComponent::class);
        $this->itemComponent = $this->gameObject->getComponent(ItemComponent::class);
        $this->characterComponent = $this->gameObject->getComponent(CharacterComponent::class);
        $this->gatheringComponent = $this->gameObject->getComponent(ResourceComponent::class);
        $this->attachedResourceComponent = $this->gameObject->getComponent(AttachedResourceComponent::class);
    }

    /** @return iterable<AbstractInteraction> */
    public function getInteractions(): iterable
    {
        if ($this->gameObject->getComponent(ResourceComponent::class)) {
            yield new GatherInteraction(new Action('app_map_resource_gather', [
                'id' => $this->gameObject->getId()
            ]));
        }

        if ($this->gameObject->getComponent(CombatComponent::class)) {
            yield new FightInteraction(new Action('app_map_mob_fight', [
                'id' => $this->gameObject->getId()
            ]));
        }
    }
}