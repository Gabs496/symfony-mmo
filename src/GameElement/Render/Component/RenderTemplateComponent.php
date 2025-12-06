<?php

namespace App\GameElement\Render\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

abstract class RenderTemplateComponent implements GameComponentInterface
{
    public function __construct(
        private string $template
    )
    {
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}