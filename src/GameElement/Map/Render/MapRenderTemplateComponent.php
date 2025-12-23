<?php

namespace App\GameElement\Map\Render;

use App\GameElement\Render\Component\RenderTemplateComponent;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class MapRenderTemplateComponent extends RenderTemplateComponent
{
    public static function getId(): string
    {
        return "map_render_component";
    }
}