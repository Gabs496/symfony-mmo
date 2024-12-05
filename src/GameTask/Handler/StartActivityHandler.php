<?php

namespace App\GameTask\Handler;

use App\Entity\Data\Activity;
use App\GameTask\Message\ActivityStepFinish;
use App\GameTask\Message\StartActivity;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

#[AsMessageHandler]
readonly class StartActivityHandler
{
    public function __construct(
        private ActivityRepository $repository,
        private MessageBusInterface $messageBus,
    )
    {

    }

    public function __invoke(StartActivity $message): void
    {
        $activity = $this->repository->find($message->getActivityId());
        if (!$activity instanceof Activity) {
            return;
        }

        $activity->setStartedAt(new DateTimeImmutable());
        $this->repository->save($activity);

        $nextStepDuration = $activity->getNextStep()->getDuration();
        $this->messageBus->dispatch(new ActivityStepFinish($activity->getId()), [
            new DelayStamp((int)bcmul($nextStepDuration, 1000, 0)),
        ]);
    }
}