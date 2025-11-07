<?php

namespace App\GameElement\Map\Twig;

use App\Entity\Game\MapObject;
use App\GameElement\Map\Render\MapRenderComponent;
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
            new TwigFunction('map_object_render', [$this, 'mapObjectRender'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function mapObjectRender(MapObject $mapObject): string
    {
        $render = $mapObject->getGameObject()->getComponent(MapRenderComponent::getId());
        if (!$render) {
            return '';
        }

        if ($render->getTemplate()) {
            return $this->renderer->createAndRender($render->getTemplate(), ['mapObject' => $mapObject]);
        }
    }
}