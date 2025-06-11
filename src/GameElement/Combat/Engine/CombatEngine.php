<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\DefenseFinished;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class CombatEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        #[AutowireIterator('combat.manager')]
        private iterable $combatManagers,
        private CacheInterface $gameObjectCache,
    )
    {

    }

    public function attack(GameObjectInterface $attacker, GameObjectInterface $defender, ?StatCollection $preCalculatedStatCollection = null): void
    {
        $attackerCombat = $attacker->getComponent(Combat::class);
        if (!$attackerCombat) {
            throw new RuntimeException(sprintf('Attacker %s does not have %s component', $attacker::class, Combat::class));
        }
        $defenderCombat = $defender->getComponent(Combat::class);
        if (!$defenderCombat) {
            throw new RuntimeException(sprintf('Defender %s does not have %s component', $defender::class, Combat::class));
        }

        $attackManager = $this->getCombatManager($attackerCombat);
        $defenderManager = $this->getCombatManager($defenderCombat);

        if ($preCalculatedStatCollection) {
            $attack = new Attack($attacker, $preCalculatedStatCollection);
        } else {
            $attack = $attackManager->generateAttack($attacker, $defender);
        }
        $this->eventDispatcher->dispatch(new AttackEvent($attack, $defender));

        $defense = $defenderManager->generateDefense($attack,$defender);
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defense));

        $attackerDispatcher = new CombatEventDispatcher();
        if ($attackManager instanceof EventSubscriberInterface) {
            $attackerDispatcher->addSubscriber($attackManager);
        }
        $defenderDispatcher = new CombatEventDispatcher();
        if ($defenderManager instanceof EventSubscriberInterface) {
            $defenderDispatcher->addSubscriber($defenderManager);
        }

        $attackResult = $defenderManager->defend($attack, $defense);
        $attackerDispatcher->dispatch($attackResult);
        $defenderDispatcher->dispatch(new DefenseFinished($attack, $defense, $attackResult));
    }

    public function getCombatManager(Combat $combat): CombatManagerInterface
    {
        //TODO: handle exception
        return $this->gameObjectCache->get('combat_manager_' . str_replace("\\", "_", $combat->getManagerClass()), function (ItemInterface $item) use ($combat) {
            foreach ($this->combatManagers as $combatManager) {
                if ($combatManager::class === $combat->getManagerClass()) {
                    return $combatManager;
                }
            }
            throw new RuntimeException('Invalid combat manager class: ' . $combat->getManagerClass());
        });
    }
}