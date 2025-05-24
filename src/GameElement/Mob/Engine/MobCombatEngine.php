<?php

namespace App\GameElement\Mob\Engine;

use App\GameElement\Mob\Event\MobDefeatEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use App\GameElement\Reward\RewardRecipe;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected RewardEngine $rewardEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MobDefeatEvent::class => [
                ['reward', 0],
            ]
        ];
    }

    public function reward(MobDefeatEvent $event): void
    {
        $mob = $event->getDefeatedMob();
        $recipe = $event->getFrom();
        if (!$recipe instanceof RewardRecipe) {
            return;
        }

        foreach ($mob->getOnDefeats() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $recipe));
        }
    }
}