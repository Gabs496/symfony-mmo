<?php

namespace App\Entity;

use App\Entity\Interface\ItemTypeInterface;
use App\Repository\ItemTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemTypeRepository::class)]
#[ORM\Table(name: 'game_item_type')]
class ItemType implements ItemTypeInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
