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
        $this->object = $object;
        $this->health = $object->getComponent(Health::class);
        $this->combat = $object->getComponent(Combat::class);
        $this->render = $object->getComponent(Render::class);
    }

    public function getHealth(): Health
    {
        return $this->health;
    }

    public function setHealth(Health $health): void
    {
        $this->health = $health;
    }

    public function getCombat(): Combat
    {
        return $this->combat;
    }

    public function setCombat(Combat $combat): void
    {
        $this->combat = $combat;
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