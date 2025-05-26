<?php

namespace App\GameElement\Reward\Engine;

use App\GameElement\Core\Token\TokenEngine;
use App\GameElement\Reward\RewardApplierInterface;
use App\GameElement\Reward\RewardApply;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(method: 'handleApply')]
class RewardEngine
{
    public function __construct(
        protected MessageBusInterface $messageBus,
        protected TokenEngine $tokenEngine,
        /** @var iterable<RewardApplierInterface> */
        #[AutowireIterator('reward.applier')]
        protected iterable $rewardAppliers,
    )
    {
    }

    public function apply(RewardApply $rewardApply): void
    {
        $rewardApply->clear();
        $this->messageBus->dispatch($rewardApply);
    }

    public function handleApply(RewardApply $rewardApply): void
    {
        $rewardApply->setRecipe($this->tokenEngine->exchange($rewardApply->getRecipeToken()));
        foreach ($this->rewardAppliers as $rewardApplier) {
            if ($rewardApplier->supports($rewardApply)) {
                $rewardApplier->apply($rewardApply);
                return;
            }
        }
        throw new RuntimeException("Reward apply not supported: " . serialize($rewardApply));
    }
}