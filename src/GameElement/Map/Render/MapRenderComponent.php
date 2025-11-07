<?php

namespace App\GameElement\Map\Render;

use App\GameElement\Render\Component\RenderComponent;

class MapRenderComponent extends RenderComponent
{
    public static function getId(): string
    {
        return "map_render_component";
    }
}