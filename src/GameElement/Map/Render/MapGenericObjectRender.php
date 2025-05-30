<?php

namespace App\GameElement\Map\Render;

use App\Entity\Game\MapObject;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'Map:GenericObjectRender')]
class MapGenericObjectRender
{
    #[ExposeInTemplate]
    protected MapObject $object;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(MapObject $object): void
    {
        $this->object = $object;
        $this->render = $this->object->getComponent(Render::class);
    }
    public function getObject(): MapObject
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