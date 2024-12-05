<?php

namespace App\GameTask\Handler;

use App\Entity\Data\Activity;
use App\GameTask\Message\ActivityStepFinish;
use App\Repository\Data\ActivityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

#[AsMessageHandler]
readonly class ActivityStepFinishHandler
{
    public function __construct(
        private ActivityRepository $repository,
        private MessageBusInterface $messageBus,
    )
    {

    }

    public function __invoke(ActivityStepFinish $message): void
    {
        $activity = $this->repository->find($message->getActivityId());
        if (!$activity instanceof Activity) {
            return;
        }

        $step = $activity->getNextStep();

        if (!$step->isCompleted()) {

            $step->setIsCompleted(true);
            $this->repository->save($activity);

            foreach ($step->getOnFinish() as $onFinish) {
                $this->messageBus->dispatch($onFinish);
            }
        }
        $activity->progressStep();
        $this->repository->save($activity);

        if (!$activity->getNextStep()) {
            $this->repository->remove($activity);
            return;
        }

        $nextStepDuration = $activity->getNextStep()->getDuration();
        $this->messageBus->dispatch(new ActivityStepFinish($activity->getId()), [
            new DelayStamp((int)bcmul($nextStepDuration, 1000, 0)),
        ]);
    }
}