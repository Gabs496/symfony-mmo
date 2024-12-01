<?php

namespace App\Twig;

use Symfony\Component\Mercure\HubInterface;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\UX\StimulusBundle\Twig\StimulusTwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly StimulusHelper $stimulusHelper,
        private readonly HubInterface $hub,
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('custom_turbo_stream_listen', [$this, 'renderTurboStreamListen'], ['is_safe' => ['html_attr']]),
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
}