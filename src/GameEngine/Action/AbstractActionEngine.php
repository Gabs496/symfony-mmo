<?php

namespace App\GameEngine\Action;

readonly abstract class AbstractActionEngine
{
    public abstract function run(object $subject, object $directObject);
}