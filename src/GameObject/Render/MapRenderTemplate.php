<?php

namespace App\GameObject\Render;

use App\Entity\Game\GameObject;
use App\Entity\Game\MapObject;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Interaction\FightInteraction;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Gathering\Interaction\GatherInteraction;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Interaction\AbstractInteraction;
use App\GameElement\Interaction\InteractableTemplateInterface;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Map\Render\MapRenderComponent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Render:MapRenderTemplate', template: 'components/Render/MapRenderTemplate.html.twig')]
class MapRenderTemplate implements InteractableTemplateInterface
{
    #[ExposeInTemplate]
    public MapObject $mapObject;

    #[ExposeInTemplate]
    public GameObject $gameObject;

    public MapRenderComponent $render;
    public ?HealthComponent $health;

    public ?StackComponent $stack = null;

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {}

    public function mount(MapObject $mapObject): void
    {
        $this->mapObject = $mapObject;
        $this->gameObject = $mapObject->getGameObject();
        $this->render = $mapObject->getGameObject()->getComponent(MapRenderComponent::getId());
        $this->stack = $this->gameObject->getComponent(StackComponent::getId());
        $this->health = $this->gameObject->getComponent(HealthComponent::getId());
    }

    /** @return iterable<AbstractInteraction> */
    public function getInteractions(): iterable
    {
        if ($this->gameObject->getComponent(GatheringComponent::getId())) {
            yield new GatherInteraction($this->urlGenerator->generate('app_map_resource_gather', [
                'id' => $this->mapObject->getId()
            ]));
        }

        if ($this->gameObject->getComponent(CombatComponent::getId())) {
            yield new FightInteraction($this->urlGenerator->generate('app_map_mob_fight', [
                'id' => $this->mapObject->getId()
            ]));
        }
    }
}