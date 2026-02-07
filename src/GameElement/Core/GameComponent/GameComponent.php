<?php

namespace App\GameElement\Core\GameComponent;

use App\GameElement\Core\GameObject\Entity\GameObject;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\OneToOne;
use LogicException;
use Symfony\Component\Uid\Uuid;

#[MappedSuperclass]
abstract class GameComponent implements GameComponentInterface
{
    #[Id]
    #[Column(type: 'guid', nullable: false)]
    protected readonly string $id;

    public function __construct(
        #[OneToOne(targetEntity: GameObject::class)]
        protected ?GameObject $gameObject = null
    )
    {
        $this->id = Uuid::v7();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGameObject(): ?GameObject
    {
        return $this->gameObject;
    }

    public function setGameObject(GameObject $gameObject): void
    {
        $this->gameObject = $gameObject;
    }

    public static function getComponentName(): string
    {
        return static::class;
    }
}