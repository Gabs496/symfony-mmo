<?php

namespace App\Twig;

use App\Engine\Math;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Render\Component\Render;
use Symfony\Component\Mercure\HubInterface;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\UX\StimulusBundle\Twig\StimulusTwigExtension;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Symfony\UX\TwigComponent\Twig\ComponentRuntime;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly StimulusHelper $stimulusHelper,
        private readonly HubInterface $hub,
        private readonly ComponentRendererInterface $renderer,
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

    public function gameObjectRender(GameObjectInterface $object): string
    {
        $render = $object->getComponent(Render::class);
        if (!$render) {
            return '';
        }

        if ($render->getTemplate()) {
            return $this->renderer->createAndRender($render->getTemplate(), ['object' => $object]);
        }

        return $this->renderer->createAndRender('Render:GenericObject', ['object' => $object]);
    }
}