<?php

namespace App\GameEngine\Activity;

readonly abstract class AbstractActivityEngine
{
    public abstract function run(object $subject, object $directObject);
}