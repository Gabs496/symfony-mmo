<?php

namespace App\GameElement\Mob\Render;

use App\Entity\Game\MapObject;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Health\Component\Health;
use App\GameElement\Map\Render\MapGenericObjectRender;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Mob:MapRender')]
class MobMapRender extends MapGenericObjectRender
{
    #[ExposeInTemplate]
    protected Health $health;

    #[ExposeInTemplate]
    protected Combat $combat;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(MapObject $object): void
    {
        parent::mount($object);
        $this->health = $object->getGameObject()->getComponent(Health::class);
        $this->combat = $object->getGameObject()->getComponent(Combat::class);
    }

    public function getHealth(): Health
    {
        return $this->health;
    }

    public function getCombat(): Combat
    {
        return $this->combat;
    }
}