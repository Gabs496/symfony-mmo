<?php

namespace App\GameElement\Render\Component;

use PennyPHP\Core\GameComponent\GameComponent;
use Attribute;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Attribute(Attribute::TARGET_CLASS)]
#[Entity]
class RenderComponent extends GameComponent
{
    public function __construct(
        #[Column(length: 50)]
        protected string $name,
        #[Column(nullable: true)]
        protected ?string $description = null,
        #[Column(length: 50, nullable: true)]
        protected ?string $iconPath = null,
    ) {
        parent::__construct();
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

    public static function getComponentName(): string
    {
        return 'render_component';
    }
}