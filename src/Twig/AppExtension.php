<?php

namespace App\Twig;

use App\Engine\Math;
use App\Entity\Data\Player;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPhp\ServerToClientEvent\Event\ClientEventInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mercure\HubInterface;
use Symfony\UX\StimulusBundle\Dto\StimulusAttributes;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Symfony\UX\StimulusBundle\Twig\StimulusTwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly StimulusHelper   $stimulusHelper,
        private readonly HubInterface     $hub,
        private readonly Security         $security,
        private readonly GameObjectEngine $gameObjectEngine,
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('custom_turbo_stream_listen', [$this, 'renderTurboStreamListen'], ['is_safe' => ['html_attr']]),
            new TwigFunction('math_stat_view', [$this, 'renderMathStatView']),
            new TwigFunction('player_map', [$this, 'getPlayerMap']),
            new TwigFunction('live_render_on', [$this, 'renderLiveRenderOn'], ['is_safe' => ['html_attr']]),
        ];
    }

    public function renderTurboStreamListen(string $topic): StimulusAttributes
    {
        $stimulusExtension = new StimulusTwigExtension($this->stimulusHelper);
        return $stimulusExtension->renderStimulusController(
            'custom_turbo_stream',
            ['hub' => $this->hub->getPublicUrl(), 'topic' => $topic]
        );
    }

    /**
     * Stimulus action that re-renders the current Live Component whenever one of the given
     * server-to-client events is received (dispatched on `document` as "server.<clientEventName>").
     */
    public function renderLiveRenderOn(ClientEventInterface|string ...$events): StimulusAttributes
    {
        $attributes = $this->stimulusHelper->createStimulusAttributes();
        foreach ($events as $event) {
            $eventName = $event instanceof ClientEventInterface ? $event->getClientEventName() : $event;
            $attributes->addAction('live', '$render', 'server.' . $eventName . '@document');
        }

        return $attributes;
    }

    public function renderMathStatView(string $value, bool $withSign = false): string
    {
        return ($withSign ? (Math::round($value) > 0 ? '+' : '-') : '') . Math::getStatViewValue($value);
    }

    public function getPlayerMap(): MapComponent
    {
        /** @var Player $player */
        $player = $this->security->getUser();
        $mapId = $player->getGameObject()->getComponent(InMapComponent::class)->getMapId();
        return $this->gameObjectEngine->get($mapId)->getComponent(MapComponent::class);
    }
}
