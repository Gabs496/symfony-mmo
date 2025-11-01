<?php

namespace App\GameElement\Gathering\Render;

use App\Entity\Game\MapObject;
use App\GameElement\Health\Component\Health;
use App\GameElement\Map\Render\MapGenericObjectRender;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Gathering:ResourceRender')]
class ResourceRender extends MapGenericObjectRender
{
    #[ExposeInTemplate]
    protected Health $health;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(MapObject $object): void
    {
        parent::mount($object);
        $this->health = $object->getGameObject()->getComponent(Health::class);
    }

    public function getHealth(): Health
    {
        return $this->health;
    }
}