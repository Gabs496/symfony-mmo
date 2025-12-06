<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Render\Component\RenderTemplateComponent;

class ItemBagRenderTemplateComponent extends RenderTemplateComponent
{
    public static function getId(): string
    {
        return "item_bag_render_component";
    }
}