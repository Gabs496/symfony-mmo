<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Render\Component\RenderComponent;

class ItemBagRenderComponent extends RenderComponent
{
    public static function getId(): string
    {
        return "item_bag_render_component";
    }
}