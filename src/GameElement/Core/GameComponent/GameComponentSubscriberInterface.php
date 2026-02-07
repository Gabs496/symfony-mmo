<?php

namespace App\GameElement\Core\GameComponent;

interface GameComponentSubscriberInterface
{
    public function getSubscribedComponents(): array;
}