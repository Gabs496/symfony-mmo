<?php

namespace App\GameElement\Map\Render;

use App\GameElement\Render\Component\RenderTemplateComponent;

class MapRenderTemplateComponent extends RenderTemplateComponent
{
    public static function getId(): string
    {
        return "map_render_component";
    }
}