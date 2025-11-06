<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;

class AttackActivity extends AbstractActivity
{
    public function __construct(
        GameObjectInterface                          $subject,
        private readonly string               $attackerToken,
        private readonly string               $defenderToken,
        private readonly ?StatCollection $preCalculatedStatCollection = null,
    )
    {
        parent::__construct($subject);
    }

    public function getAttackerToken(): string
    {
        return $this->attackerToken;
    }

    public function getPreCalculatedStatCollection(): ?StatCollection
    {
        return $this->preCalculatedStatCollection;
    }

    public function getDefenderToken(): string
    {
        return $this->defenderToken;
    }
}