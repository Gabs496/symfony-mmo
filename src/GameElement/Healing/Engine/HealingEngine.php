<?php

namespace App\GameElement\Healing\Engine;

use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Character\Engine\HealthEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Healing\Component\HealingComponent;
use InvalidArgumentException;

class HealingEngine
{
    public function __construct(
        protected HealthEngine $healthEngine,
    )
    {

    }

    public function heal(GameObjectInterface $subject, HealingComponent $healing): CharacterComponent
    {
        if (!$subject->hasComponent(CharacterComponent::class)) {
            throw new InvalidArgumentException(sprintf('Subject %s does not have a character component.', $subject));
        }

        $this->healthEngine->modifyCurrentHealth($subject, $healing->getAmount());

        return $subject->getComponent(CharacterComponent::class);
    }
}