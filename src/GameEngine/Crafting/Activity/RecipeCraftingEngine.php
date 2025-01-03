<?php

namespace App\GameEngine\Crafting\Activity;

use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameEngine\Activity\AbstractActivityEngine;
use App\GameEngine\Engine;
use App\GameEngine\Reward\PlayerRewardEngine;
use App\GameObject\Activity\ActivityType;
use App\GameObject\Activity\RecipeCraftingActivity;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AutoconfigureTag('game.engine.action')]
#[Engine(RecipeCraftingActivity::class)]
readonly class RecipeCraftingEngine extends AbstractActivityEngine
{
    public function __construct(
        private ActivityRepository  $activityRepository,
        private MessageBusInterface $messageBus,
        private PlayerRewardEngine  $playerRewardEngine,
    )
    {
    }

    /**
     * @psalm-param  PlayerCharacter[] $who
     * @psalm-param   AbstractRecipe $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $character = $subject;
        $activity = (new Activity(ActivityType::RECIPE_CRAFTING));

        $step = new ActivityStep($directObject->getCraftingTime());
        $activity->addStep($step);

        $activity->applyMasteryPerformance($character->getMasterySet());

        //TODO: lock player activity

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

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

            $this->playerRewardEngine->reward($character->getId(), $directObject->getRewards());

            $activity->progressStep();
            $this->activityRepository->save($activity);
        }

        $this->activityRepository->remove($activity);
    }

    private function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }

    public static function getId(): string
    {
        return self::class;
    }
}