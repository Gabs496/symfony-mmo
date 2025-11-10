<?php

namespace App\Twig\Components;

use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Core\GameObject\GameObjectInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class CombatStats
{
    public GameObjectInterface $gameObject;
    public ?CombatComponent $combat = null;

    public function mount(GameObjectInterface $gameObject): void
    {
        $this->gameObject = $gameObject;
        $this->combat = $gameObject->getComponent(CombatComponent::class);
    }
}
