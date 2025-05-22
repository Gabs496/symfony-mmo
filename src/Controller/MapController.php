<?php

namespace App\Controller;

use App\Engine\Gathering\Activity\ResourceGatheringActivity;
use App\Engine\Item\ItemActionEngine;
use App\Engine\Mob\MobToken;
use App\Engine\Player\PlayerCombatManager;
use App\Engine\Player\PlayerToken;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapSpawnedMob;
use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
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
            'spawnedResources' => $mapEngine->getSpawnedResources($user->getMap()),
            'recipes' => $gameObjectEngine->getByClass(AbstractRecipe::class),
            'spawnedMobs' => $mapEngine->getSpawnedMobs($user->getMap()),
        ]);
    }

    #[Route('/resource_gather/{id}', name: 'app_map_resource_gather')]
    public function startGathering(MapSpawnedResource $spawnedResource, ActivityEngine $gameActivity, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource

        $gameActivity->run(new ResourceGatheringActivity(new PlayerToken($player->getId()), $spawnedResource->getResource(), $spawnedResource->getId()));

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
        $gameActivity->run(new RecipeCraftingActivity(new PlayerToken($user->getId()), $recipe));

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/mob-fight/{id}', name: 'app_map_mob_fight')]
    public function startMobFight(ActivityEngine $gameActivity, Request $request, MapSpawnedMob $mapSpawnedMob, PlayerCombatManager $combatEngine): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $gameActivity->run($combatEngine->generateAttackActivity($player, new MobToken($mapSpawnedMob->getId())));

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