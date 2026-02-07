<?php

namespace App\GameElement\Item\Twig;

use App\GameElement\Core\GameObject\Entity\GameObject;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ItemExtension extends AbstractExtension
{
    public function __construct(
        private readonly ComponentRendererInterface $renderer
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('item_object_render', [$this, 'itemRender'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function itemRender(GameObject $item): string
    {
        return $this->renderer->createAndRender('Render:ItemBagRenderTemplate', ['item' => $item]);
    }
}