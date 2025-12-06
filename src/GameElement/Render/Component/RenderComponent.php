<?php

namespace App\GameElement\Render\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class RenderComponent implements GameComponentInterface
{
    public function __construct(
        protected string $name,
        protected ?string $description = null,
        protected ?string $iconPath = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getIconPath(): ?string
    {
        return $this->iconPath;
    }

    public function setIconPath(?string $iconPath): void
    {
        $this->iconPath = $iconPath;
    }

    public static function getId(): string
    {
        return 'render_component';
    }
}