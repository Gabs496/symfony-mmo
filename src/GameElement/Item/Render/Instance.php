<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Item\AbstractItemInstance;
use App\GameElement\Render\Component\Render;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: "Item:Instance")]
class Instance
{
    #[ExposeInTemplate]
    protected AbstractItemInstance $instance;

    #[ExposeInTemplate]
    protected Render $render;

    public function mount(AbstractItemInstance $instance): void
    {
        $this->instance = $instance;
        $this->render = $instance->getComponent(Render::class);
    }

    public function getInstance(): AbstractItemInstance
    {
        return $this->instance;
    }

    public function setInstance(AbstractItemInstance $instance): void
    {
        $this->instance = $instance;
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