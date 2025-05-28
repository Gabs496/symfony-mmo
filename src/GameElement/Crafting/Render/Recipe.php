<?php

namespace App\GameElement\Crafting\Render;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameElement\Item\Engine\ItemPrototypeEngine;
use Generator;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'Crafting:Recipe')]
class Recipe
{
    protected AbstractRecipe $recipe;

    //TODO: remove from this domain. Need to manage a single component
    protected PlayerCharacter $playerCharacter;

    public function __construct(
        protected ItemPrototypeEngine $itemPrototypeEngine,
    )
    {
    }

    public function getRecipe(): AbstractRecipe
    {
        return $this->recipe;
    }

    public function setRecipe(AbstractRecipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function getPlayerCharacter(): PlayerCharacter
    {
        return $this->playerCharacter;
    }

    public function setPlayerCharacter(PlayerCharacter $playerCharacter): void
    {
        $this->playerCharacter = $playerCharacter;
    }

    public function getItemRewardeds(): Generator
    {
        foreach ($this->recipe->getRewards() as $reward) {
            if ($reward instanceof ItemReward) {
                yield $this->itemPrototypeEngine->get($reward->getItemPrototypeId());
            }
        }
    }
}