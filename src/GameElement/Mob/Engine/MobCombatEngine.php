<?php

namespace App\GameElement\Mob\Engine;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MobCombatEngine implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}