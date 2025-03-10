<?php

namespace App\GameElement\Crafting\Activity\Engine;

use App\Engine\Player\PlayerEngine;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Core\EngineFor;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/** @extends AbstractActivityEngine<PlayerCharacter,RecipeCraftingActivity> */
#[AutoconfigureTag('game.engine.action')]
#[EngineFor(RecipeCraftingActivity::class)]
readonly class RecipeCraftingEngine extends AbstractActivityEngine
{
    private PlayerEngine $playerEngine;

    public function __construct(
        ActivityRepository  $activityRepository,
        MessageBusInterface $messageBus,
        PlayerEngine $playerEngine,
        EventDispatcherInterface $eventDispatcher,
    )
    {
        parent::__construct($activityRepository, $messageBus, $eventDispatcher);
        $this->playerEngine = $playerEngine;
    }

    public static function getId(): string
    {
        return self::class;
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     */
    public function generateSteps(object $subject, object $directObject): iterable
    {
        yield new ActivityStep($directObject->getCraftingTime());
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws Throwable
     */
    #[AsEventListener(BeforeActivityStartEvent::class)]
    public  function beforeActivityStart(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof RecipeCraftingActivity) {
            return;
        }

        $this->takeIngredient($event->getSubject(), $activity->getRecipe());
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws UserNotificationException
     */
    private function takeIngredient(object $subject, object $directObject): void
    {
        try {
            foreach ($directObject->getIngredients() as $ingredient) {
                $this->playerEngine->takeItem($subject, $ingredient->getItem(), $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($subject->getId(),'Recipe ingredients not availables');
        }
    }
}