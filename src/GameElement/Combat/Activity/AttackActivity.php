<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;

class AttackActivity extends AbstractActivity
{
    public function __construct(
        TokenizableInterface                          $subject,
        private readonly TokenInterface               $attackerToken,
        private readonly TokenInterface               $defenderToken,
        private readonly ?StatCollection $preCalculatedStatCollection = null,
    )
    {
        parent::__construct($subject);
    }

    public function getAttackerToken(): TokenInterface
    {
        return $this->attackerToken;
    }

    public function getPreCalculatedStatCollection(): ?StatCollection
    {
        return $this->preCalculatedStatCollection;
    }

    public function getDefenderToken(): TokenInterface
    {
        return $this->defenderToken;
    }
}