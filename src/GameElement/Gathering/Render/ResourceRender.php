<?php

namespace App\GameElement\Gathering\Render;

use App\GameElement\Map\Render\MapGenericObjectRender;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'Gathering:ResourceRender')]
class ResourceRender extends MapGenericObjectRender
{

}