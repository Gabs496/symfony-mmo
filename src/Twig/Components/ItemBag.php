<?php

namespace App\Twig\Components;

use App\GameElement\Core\GameObject\Entity\GameObject;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ItemBag
{
    public GameObject $player;


    public function getContents(): array
    {

    }
}
