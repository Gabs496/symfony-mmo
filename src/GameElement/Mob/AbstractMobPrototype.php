<?php

namespace App\GameElement\Mob;

use App\Entity\Core\GameObject;
use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Mob\Combat\MobCombatManager;
use App\GameElement\Render\Component\RenderComponent;
use App\GameElement\Reward\RewardInterface;
use RuntimeException;

abstract class AbstractMobPrototype implements GameObjectPrototypeInterface
{
    public function make(
        array  $components = [],
        string $name = 'Mob',
        string $description = '',
        string $iconPath = '',
        float  $maxHealth = 0.0,
        /** @param AbstractStat[] $combatStats */
        array  $combatStats = [],
    ): GameObject
    {
        try {
            $gameObject = new GameObject($this, $components);
            $gameObject
                ->setComponent(new RenderComponent($name, $description, $iconPath))
                ->setComponent(new HealthComponent($maxHealth, $maxHealth))
                ->setComponent(new CombatComponent($combatStats, MobCombatManager::getId()));
            return $gameObject;
        } catch (InvalidGameComponentException $e) {
            throw new RuntimeException("Cannot make " . $this::class . ": " . $e->getMessage(), 0, $e);
        }
    }

    /** @return RewardInterface[] */
    public abstract function getRewardOnDefeats(): array;
}