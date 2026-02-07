<?php

namespace App\GameElement\Map\Twig;

use App\GameElement\Core\GameObject\Entity\GameObject;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MapExtension extends AbstractExtension
{
    public function __construct(
        private readonly ComponentRendererInterface $renderer
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('map_object_render', [$this, 'gameObjectRender'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function gameObjectRender(GameObject $gameObject): string
    {
        return $this->renderer->createAndRender('Render:MapRenderTemplate', ['mapObject' => $gameObject]);
    }
}