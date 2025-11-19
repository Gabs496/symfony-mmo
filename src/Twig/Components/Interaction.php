<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Interaction
{
    public iterable $interactions = [];
    public bool $asDropdown = true;
}
