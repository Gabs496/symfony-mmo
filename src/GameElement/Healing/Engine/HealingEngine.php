<?php

namespace App\GameElement\Healing\Engine;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Healing\Component\Healing;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Engine\HealthEngine;
use InvalidArgumentException;

class HealingEngine
{
    public function __construct(
        protected HealthEngine $healthEngine,
    )
    {

    }

    public function heal(GameObjectInterface $subject, Healing $healing): Health
    {
        if (!$subject->hasComponent(Health::class)) {
            throw new InvalidArgumentException(sprintf('Subject %s does not have a health component.', $subject));
        }

        $this->healthEngine->modifyCurrentHealth($subject, $healing->getAmount());

        return $subject->getComponent(Health::class);
    }
}