<?php

namespace App\GameElement\Crafting\Activity\Engine;

use App\Core\Engine;
use App\Engine\Reward\PlayerRewardEngine;
use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityInvolvableInterface;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\GameObject\Activity\ActivityType;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

/** @extends AbstractActivityEngine<PlayerCharacter,RecipeCraftingActivity> */
#[AutoconfigureTag('game.engine.action')]
#[Engine(RecipeCraftingActivity::class)]
readonly class RecipeCraftingEngine extends AbstractActivityEngine
{
    public function __construct(
        private ActivityRepository  $activityRepository,
        private MessageBusInterface $messageBus,
        private PlayerRewardEngine  $playerRewardEngine,)
    {
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $character = $subject;
        $activity = (new Activity(ActivityType::RECIPE_CRAFTING));

        $this->takeIngredient($subject, $directObject);

        foreach ($this->generateSteps($character, $directObject) as $generatedStep) {
            $activity->addStep($generatedStep);
        }

        $activity->applyMasteryPerformance($character->getMasterySet());

        if ($subject instanceof ActivityInvolvableInterface) {
            $subject->startActivity($activity);
        }
        $activity->setStartedAt(new DateTimeImmutable());
        $this->activityRepository->save($activity);

        while ($step = $activity->getNextStep()) {

            $step->setScheduledAt(microtime(true));
            $this->activityRepository->save($activity);
            $this->messageBus->dispatch(new BroadcastActivityStatusChange($activity->getId()));

            $this->waitForStepFinish($step);

            $activity = $this->activityRepository->find($activity->getId());
            if (!$activity instanceof Activity) {
                return;
            }

            $this->onStepFinish($character, $directObject, $step);

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

            $activity->progressStep();
            $this->activityRepository->save($activity);
        }

        if ($subject instanceof ActivityInvolvableInterface) {
            $subject->endActivity($activity);
        }
        $this->activityRepository->remove($activity);
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
        $this->playerRewardEngine->reward($subject->getId(), $directObject->getRewards());
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