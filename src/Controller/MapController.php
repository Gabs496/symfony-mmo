<?php

namespace App\Controller;

use App\Engine\Item\ItemActionEngine;
use App\Engine\Player\PlayerCombatManager;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapObject;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Map\Engine\MapEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/map')]
class MapController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_map')]
    #[IsGranted('ROLE_USER')]
    public function home(MapEngine $mapEngine, GameObjectEngine $gameObjectEngine): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'player' => $user,
            'mapObjects' => $mapEngine->getMapObjects($user->getMap()),
            'recipes' => $gameObjectEngine->getByClass(AbstractRecipe::class),
        ]);
    }

    #[Route('/resource_gather/{id}', name: 'app_map_resource_gather')]
    public function startGathering(MapObject $resource, ActivityEngine $gameActivity, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource

        $gameActivity->run(new ResourceGatheringActivity($player, $resource->getGameObject()));

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/craft/{id}', name: 'app_map_craft')]
    public function startCraftingRecipe(ActivityEngine $gameActivity, GameObjectEngine $gameObjectEngine, string $id, Request $request): Response
    {
        /** @var AbstractRecipe $recipe */
        $recipe = $gameObjectEngine->get($id);
        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $gameActivity->run(new RecipeCraftingActivity($user, $recipe));

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/mob-fight/{id}', name: 'app_map_mob_fight')]
    public function startMobFight(ActivityEngine $gameActivity, Request $request, MapObject $mob, PlayerCombatManager $combatEngine): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $gameActivity->run($combatEngine->generateAttackActivity($player, $mob->getGameObject()));

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item_action_perform/{id}/{action}', name: 'app_map_item_action_perform')]
    public function itemActionPerform(ItemInstance $itemInstance, string $action, ItemActionEngine $itemActionEngine, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $itemActionEngine->performItemAction($player, new $action(), $itemInstance, [$player]);

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}