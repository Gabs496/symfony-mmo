<?php

namespace App\Engine\MapAvailableActivity\Event\Handler;

use App\Engine\MapAvailableActivity\Event\ConsumeMapAvailableActivity;
use App\Entity\Data\MapAvailableActivity;
use App\Repository\Data\MapAvailableActivityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ConsumeMapAvailableActivityHandler
{
    public function __construct(private MapAvailableActivityRepository $repository)
    {
    }

    public function __invoke(ConsumeMapAvailableActivity $consumeMapAvailableActivity): void
    {
        $mapAvailableActivity = $this->repository->find($consumeMapAvailableActivity->getMapAvailableActivityId());

        if (!$mapAvailableActivity instanceof MapAvailableActivity) {
            return;
        }

        $mapAvailableActivity->consume($consumeMapAvailableActivity->getQuantity());
        if ($mapAvailableActivity->isEmpty()) {
            $this->repository->remove($mapAvailableActivity);
            return;
        }
        $this->repository->save($mapAvailableActivity);
    }
}