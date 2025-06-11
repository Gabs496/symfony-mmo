<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\Token\TokenEngine;
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

    public function onComplete(AbstractActivity $activity): void
    {
        $this->startAttack($activity);
    }

    public function onFinish(AbstractActivity $activity): void
    {
        return;
    }

    public function cancel(AbstractActivity $activity): void
    {
        // TODO: Implement cancel() method.
    }

    protected function startAttack(AttackActivity $activity): void
    {
        $attackerToken = $activity->getAttackerToken();
        $defenderToken = $activity->getDefenderToken();

        $attacker = $this->tokenEngine->exchange($attackerToken);
        $defender = $this->tokenEngine->exchange($defenderToken);

        if (!$attacker instanceof GameObjectInterface || !$defender instanceof GameObjectInterface) {
            throw new RuntimeException(sprintf("Both %s and %s must implement %s", $attacker::class, $defender::class, GameObjectInterface::class));
        }

        $this->combatEngine->attack($attacker, $defender, $activity->getPreCalculatedStatCollection());
    }
}