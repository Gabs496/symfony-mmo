<?php

namespace App\GameElement\Health\Render;

use App\GameElement\Health\Component\Health;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: "Health:Bar")]
class Bar
{
    protected Health $health;

    public function getHealth(): Health
    {
        return $this->health;
    }

    public function setHealth(Health $health): void
    {
        $this->health = $health;
    }
}