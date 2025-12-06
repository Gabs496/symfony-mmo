<?php

namespace App\Twig\Components\Render;

use App\Entity\Core\GameObject;
use App\Entity\Map\MapObject;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Interaction\FightInteraction;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Gathering\Interaction\GatherInteraction;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\Action;
use App\GameElement\Interaction\InteractableTemplateInterface;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Render\Component\RenderComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Render:MapRenderTemplate', template: 'components/Render/MapRenderTemplate.html.twig')]
class MapRenderTemplate implements InteractableTemplateInterface
{
    #[ExposeInTemplate]
    public MapObject $mapObject;

    #[ExposeInTemplate]
    public GameObject $gameObject;

    public RenderComponent $render;
    public ?HealthComponent $health;

    public ?StackComponent $stack = null;
    public function mount(MapObject $mapObject): void
    {
        $this->mapObject = $mapObject;
        $this->gameObject = $mapObject->getGameObject();
        $this->render = $mapObject->getGameObject()->getComponent(RenderComponent::class);
        $this->stack = $this->gameObject->getComponent(StackComponent::class);
        $this->health = $this->gameObject->getComponent(HealthComponent::class);
    }

    /** @return iterable<AbstractInteraction> */
    public function getInteractions(): iterable
    {
        if ($this->gameObject->getComponent(GatheringComponent::class)) {
            yield new GatherInteraction(new Action('app_map_resource_gather', [
                'id' => $this->mapObject->getId()
            ]));
        }

        if ($this->gameObject->getComponent(CombatComponent::class)) {
            yield new FightInteraction(new Action('app_map_mob_fight', [
                'id' => $this->mapObject->getId()
            ]));
        }
    }
}