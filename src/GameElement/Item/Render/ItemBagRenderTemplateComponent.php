<?php

namespace App\GameElement\Item\Render;

use App\GameElement\Render\Component\RenderTemplateComponent;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ItemBagRenderTemplateComponent extends RenderTemplateComponent
{
    public static function getId(): string
    {
        return "item_bag_render_component";
    }
}