<?php

namespace App\GameElement\Mob\Engine;

use App\GameElement\Drop\Component\Drop;
use App\GameElement\Drop\Engine\DropEngine;
use App\GameElement\Mob\AbstractMob;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected RewardEngine $rewardEngine,
        protected DropEngine $dropEngine,
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
        /** @var AbstractMob $mob */
        $mob = $event->getDefeatedMob()->getPrototype();
        foreach ($mob->getRewardOnDefeats() as $reward) {
            foreach ($reward->getAttributes() as $attribute){
                if ($attribute instanceof Drop) {
                    if (!$this->dropEngine->isLucky($attribute)) {
                        return;
                    }
                }
            }
            $this->rewardEngine->apply(new RewardApply($reward, $event->getFrom()));
        }
    }
}