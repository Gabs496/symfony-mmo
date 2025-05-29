<?php

namespace App\GameElement\Render;

use App\Entity\Game\MapObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Render:GenericObject')]
class GenericObject
{
    #[ExposeInTemplate]
    protected GameObjectInterface $object;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(MapObject $object): void
    {
        $this->object = $object;
        $this->render = $this->object->getComponent(Render::class);
    }
    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }

    public function setObject(MapObject $object): void
    {
        $this->object = $object;
    }

    public function getRender(): Render
    {
        return $this->render;
    }
}