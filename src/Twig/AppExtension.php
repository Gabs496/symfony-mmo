<?php

namespace App\Twig;

use App\Engine\Math;
use App\Entity\Game\MapObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Render\Component\Render;
use Symfony\Component\Mercure\HubInterface;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\UX\StimulusBundle\Twig\StimulusTwigExtension;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly StimulusHelper $stimulusHelper,
        private readonly HubInterface $hub,
        private readonly ComponentRendererInterface $renderer
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('custom_turbo_stream_listen', [$this, 'renderTurboStreamListen'], ['is_safe' => ['html_attr']]),
            new TwigFunction('math_stat_view', [$this, 'renderMathStatView']),
            new TwigFunction('game_object_render', [$this, 'gameObjectRender'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function renderTurboStreamListen(string $topic): string
    {
        $stimulusExtension = new StimulusTwigExtension($this->stimulusHelper);
        return $stimulusExtension->renderStimulusController(
            'custom_turbo_stream',
            ['hub' => $this->hub->getPublicUrl(), 'topic' => $topic, 'token' => $this->hub->getFactory()->create([$topic], [$topic])]
        );
    }

    public function renderMathStatView(string $value): string
    {
        return Math::getStatViewValue($value);
    }

    //TODO: split logic
    public function gameObjectRender(MapObject $mapObject): string
    {
        $render = $mapObject->getGameObject()->getComponent(Render::class);
        if (!$render) {
            return '';
        }

        if ($render->getTemplate()) {
            return $this->renderer->createAndRender($render->getTemplate(), ['object' => $mapObject]);
        }

        return $this->renderer->createAndRender('Render:GenericObject', ['object' => $mapObject]);
    }
}