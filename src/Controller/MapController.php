<?php

namespace App\Controller;

use App\Engine\Player\PlayerItemEngine;
use App\Entity\Data\Player;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Crafting\CraftingIngredient;
use App\GameElement\Crafting\Engine\CraftingEngine;
use App\GameElement\Crafting\Engine\RecipeBook;
use App\GameElement\Crafting\Exception\IngredientNotAvailableException;
use App\GameElement\Gathering\Engine\GatheringEngine;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Repository\InMapRepository;
use App\GameElement\Notification\Exception\UserNotificationException;
use PennyPHP\Core\Entity\GameObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/map')]
class MapController extends AbstractController
{
    public function __construct(
        private readonly InMapRepository $inMapRepository,
        private readonly CraftingEngine  $craftingEngine,
        private readonly GatheringEngine $gatheringEngine,
        private readonly PlayerItemEngine $playerItemEngine,
        private readonly RecipeBook $recipeBook,
    )
    {

    }

    #[Route('/', name: 'app_map')]
    #[IsGranted('ROLE_USER')]
    public function home(): Response
    {
        /** @var Player $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'player' => $user,
            'recipes' => $this->recipeBook->getRecipes(),
        ]);
    }

    #[Route('/map/field', name: 'app_map_field')]
    #[IsGranted('ROLE_USER')]
    public function field(): Response
    {
        /** @var Player $user */
        $user = $this->getUser();

        return $this->renderBlock('streams/map_field.stream.html.twig', 'renderMap', [
            'player' => $user,
        ]);
    }

    #[Route('/resource_gather/{id}', name: 'app_map_resource_gather')]
    #[IsGranted('ROLE_USER')]
    public function startGathering(GameObject $resource, Request $request): Response
    {
        /** @var Player $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource
        $this->gatheringEngine->startGathering($player->getGameObject(), $resource);

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/craft/{id}', name: 'app_map_craft')]
    #[IsGranted('ROLE_USER')]
    public function startCraftingRecipe(string $id, Request $request): Response
    {
        /** @var Player $user */
        $user = $this->getUser();
        $recipe = $this->recipeBook->getRecipe($id);

        /** @var array<CraftingIngredient> $ingredients */
        $ingredients = [];
        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                $itemTakeEvents = $this->playerItemEngine->takeFromBackpack($user->getGameObject(), $ingredient->getPrototype()::getType(), $ingredient->getQuantity());
                foreach ($itemTakeEvents as $itemTake) {
                    $ingredients[] = new CraftingIngredient($itemTake->getItem(), $itemTake->getQuantity());
                }
            }
        } catch (ItemQuantityNotAvailableException $event) {
            throw new UserNotificationException($user->getId(), 'Recipe ingredients not availables', previous: $event);
        }

        try {
            $this->craftingEngine->startCrafting($user->getGameObject(), $id, $ingredients);
        } catch (IngredientNotAvailableException $event) {
            throw new UserNotificationException($user->getId(), $event->getMessage());
        }

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/mob-fight/{id}', name: 'app_map_mob_fight')]
    #[IsGranted('ROLE_USER')]
    public function startMobFight(Request $request, InMapComponent $mob, CombatEngine $combatEngine): Response
    {
        /** @var Player $player */
        $player = $this->getUser();
        $combatEngine->startAttack($player->getGameObject(), $mob->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}