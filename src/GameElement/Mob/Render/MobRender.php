<?php

namespace App\GameElement\Mob\Render;

use App\GameElement\Combat\Component\Combat;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Mob:MobRender')]
class MobRender
{
    #[ExposeInTemplate]
    protected GameObjectInterface $mob;

    #[ExposeInTemplate]
    protected Health $health;

    #[ExposeInTemplate]
    protected Combat $combat;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(GameObjectInterface $object): void
    {
        $this->mob = $object;
        $this->health = $object->getComponent(Health::class);
        $this->combat = $object->getComponent(Combat::class);
        $this->render = $object->getComponent(Render::class);
    }

    public function getMob(): GameObjectInterface
    {
        return $this->mob;
    }

    public function setMob(GameObjectInterface $mob): void
    {
        $this->mob = $mob;
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