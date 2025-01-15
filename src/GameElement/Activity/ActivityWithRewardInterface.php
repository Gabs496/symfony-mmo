<?php

namespace App\GameElement\Activity;

interface ActivityWithRewardInterface
{
    public function getRewards(): iterable;
}