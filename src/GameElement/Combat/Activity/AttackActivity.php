<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;

class AttackActivity extends AbstractActivity
{
    private string $defenderToken;

    public function __construct(
        GameObjectInterface              $subject,
        private ?GameObjectInterface      $defender,
        private readonly ?StatCollection $preCalculatedStatCollection = null,
    )
    {
        parent::__construct($subject);
        $this->defenderToken = $defender->getId();
    }

    public function getDefender(): ?GameObjectInterface
    {
        return $this->defender;
    }

    public function getPreCalculatedStatCollection(): ?StatCollection
    {
        return $this->preCalculatedStatCollection;
    }

    public function getDefenderToken(): string
    {
        return $this->defenderToken;
    }

    public function clear(): void
    {
        parent::clear();
        $this->defender = null;
    }
}