<?php

namespace App\GameElement\Item\Twig;

use App\Entity\Data\ItemObject;
use App\GameElement\Item\Render\ItemBagRenderComponent;
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
            new TwigFunction('item_object_render', [$this, 'itemObjectRender'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function itemObjectRender(ItemObject $itemObject): string
    {
        $render = $itemObject->getGameObject()->getComponent(ItemBagRenderComponent::class);
        if (!$render) {
            return '';
        }

        if ($render->getTemplate()) {
            return $this->renderer->createAndRender($render->getTemplate(), ['itemObject' => $itemObject]);
        }
    }
}