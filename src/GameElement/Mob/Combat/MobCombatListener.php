<?php

namespace App\GameElement\Mob\Combat;

use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Drop\Component\Drop;
use App\GameElement\Drop\Engine\DropEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\GameElement\Mob\MobPrototypeInterface;
use App\GameElement\Reward\Engine\RewardEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MobCombatListener implements EventSubscriberInterface
{

    public function __construct(
        private readonly RewardEngine     $rewardEngine,
        private readonly DropEngine       $dropEngine,
        private readonly GameObjectEngine $gameObjectEngine,
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
        /** @var MobPrototypeInterface $mob */
        $mob = $this->gameObjectEngine->getPrototype($event->getDefeatedMob()->getPrototype());
        foreach ($mob->getRewardOnDefeats() as $reward) {
            foreach ($reward->getAttributes() as $attribute){
                if ($attribute instanceof Drop) {
                    if (!$this->dropEngine->isLucky($attribute)) {
                        return;
                    }
                }
            }
            $this->rewardEngine->apply($reward, $event->getFrom());
        }
    }
}