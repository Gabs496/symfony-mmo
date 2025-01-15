<?php

namespace App\GameElement\Crafting\Activity\Engine;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Core\EngineFor;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/** @extends AbstractActivityEngine<PlayerCharacter,RecipeCraftingActivity> */
#[AutoconfigureTag('game.engine.action')]
#[EngineFor(RecipeCraftingActivity::class)]
readonly class RecipeCraftingEngine extends AbstractActivityEngine
{
    public function __construct(
        ActivityRepository  $activityRepository,
        MessageBusInterface $messageBus,
    )
    {
        parent::__construct($activityRepository, $messageBus);
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
     * @param object $subject
     * @param object $directObject
     * @param ActivityStep $step
     * @throws Throwable
     */
    public function onStepFinish(object $subject, object $directObject, ActivityStep $step): void
    {
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws Throwable
     */
    public  function onStepStart(object $subject, object $directObject): void
    {
        $this->takeIngredient($subject, $directObject);
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
                $subject->getBackpack()->extract($ingredient->getItem(), $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($subject->getId(),'Recipe ingredients not availables');
        }
    }
}