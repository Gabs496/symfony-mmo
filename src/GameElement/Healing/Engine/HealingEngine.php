<?php

namespace App\GameElement\Healing\Engine;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Health\Engine\HealthEngine;
use InvalidArgumentException;

class HealingEngine
{
    public function __construct(
        protected HealthEngine $healthEngine,
    )
    {

    }

    public function heal(GameObjectInterface $subject, HealingComponent $healing): HealthComponent
    {
        if (!$subject->hasComponent(HealthComponent::getId())) {
            throw new InvalidArgumentException(sprintf('Subject %s does not have a health component.', $subject));
        }

        $this->healthEngine->modifyCurrentHealth($subject, $healing->getAmount());

        return $subject->getComponent(HealthComponent::getId());
    }
}