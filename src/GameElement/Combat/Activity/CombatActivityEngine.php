<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Engine\CombatEngine;
use PennyPHP\Core\GameObject\GameObjectInterface;
use PennyPHP\Core\Token\TokenEngine;
use RuntimeException;

/** @extends ActivityEngineExtensionInterface<AttackActivity> */
readonly class CombatActivityEngine implements ActivityEngineExtensionInterface
{
    public function __construct(
        private TokenEngine $tokenEngine,
        private CombatEngine $combatEngine,
    )
    {
    }

    public function supports(AbstractActivity $activity): bool
    {
        return $activity instanceof AttackActivity;
    }

    public function getDuration(AbstractActivity $activity): float
    {
        return 1.0;
    }

    public function beforeStart(AbstractActivity $activity): void
    {
        return;
    }

    /** @param AttackActivity $activity */
    public function onComplete(AbstractActivity $activity): void
    {

        $attackerToken = $activity->getSubjectToken();
        $defenderToken = $activity->getDefenderToken();

        $attacker = $this->tokenEngine->exchange($attackerToken);
        $defender = $this->tokenEngine->exchange($defenderToken);

        $this->combatEngine->attack($attacker, $defender);
    }

    public function onFinish(AbstractActivity $activity): void
    {
        return;
    }

    public function cancel(AbstractActivity $activity): void
    {
        return;
    }
}