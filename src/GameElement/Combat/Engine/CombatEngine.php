<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Core\GameObject\GameObjectInterface;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class CombatEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityEngine $activityEngine,
        private CacheInterface $gameObjectCache,
        #[AutowireIterator('combat.manager')]
        /** @var iterable<CombatManagerInterface> */
        private iterable $combatManagers,
    )
    {

    }

    public function startAttack(GameObjectInterface $attacker, GameObjectInterface $defender): void
    {
        $this->activityEngine->run(new AttackActivity($attacker, $defender));
    }

    public function attack(GameObjectInterface $attacker, GameObjectInterface $defender): void
    {
        $attackerCombat = $attacker->getComponent(CombatComponent::class);
        if (!$attackerCombat) {
            throw new RuntimeException(sprintf('Attacker %s does not have %s component', $attacker::class, CombatComponent::class));
        }
        $defenderCombat = $defender->getComponent(CombatComponent::class);
        if (!$defenderCombat) {
            throw new RuntimeException(sprintf('Defender %s does not have %s component', $defender::class, CombatComponent::class));
        }

        $attack = self::generateAttack($attacker, $defender);

        $attackManager = $this->getCombatManager($attackerCombat);
        $defenderManager = $this->getCombatManager($defenderCombat);

        $this->eventDispatcher->dispatch(new AttackEvent($attack, $defender));

        $defense = $defenderManager->generateDefense($attack,$defender);
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defense));

        $attackResult = $defenderManager->defend($attack, $defense);
        $attackManager->afterAttack($attackResult);
        $defenderManager->afterDefense($attackResult);
    }

    private function getCombatManager(CombatComponent $combat): CombatManagerInterface
    {
        return $this->gameObjectCache->get('combat.manager.' . $combat->getManagerId(), function(ItemInterface $item) use ($combat) {
            foreach ($this->combatManagers as $combatManager) {
                if ($combatManager::getId() === $combat->getManagerId()) {
                    return $combatManager;
                }
            }
            throw new RuntimeException('Invalid combat manager class: ' . $combat->getManagerId());
        });
    }

    private function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack
    {
        return self::getCombatManager($attacker->getComponent(CombatComponent::class))->generateAttack($attacker, $defender);
    }
}