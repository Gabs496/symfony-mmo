<?php

namespace App\Controller;

use App\Engine\Player\PlayerCombatManager;
use App\Engine\Player\PlayerCraftingEngine;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapObject;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Engine\GatheringEngine;
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
    #[Route('/', name: 'app_map')]
    #[IsGranted('ROLE_USER')]
    public function home(GameObjectEngine $gameObjectEngine): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'player' => $user,
            'recipes' => $gameObjectEngine->getByClass(AbstractRecipe::class),
        ]);
    }

    #[Route('/map/field', name: 'app_map_field')]
    #[IsGranted('ROLE_USER')]
    public function field(MapEngine $mapEngine): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/field.html.twig', [
            'mapObjects' => $mapEngine->getMapObjects($user->getMap()),
        ]);
    }

    #[Route('/resource_gather/{id}', name: 'app_map_resource_gather')]
    #[IsGranted('ROLE_USER')]
    public function startGathering(MapObject $resource, GatheringEngine $gatheringEngine, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource
        $gatheringEngine->startGathering($player, $resource->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/craft/{id}', name: 'app_map_craft')]
    #[IsGranted('ROLE_USER')]
    public function startCraftingRecipe(GameObjectEngine $gameObjectEngine, PlayerCraftingEngine $craftingEngine, string $id, Request $request): Response
    {
        /** @var AbstractRecipe $recipe */
        $recipe = $gameObjectEngine->get($id);
        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $craftingEngine->startCrafting($user, $recipe);

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/mob-fight/{id}', name: 'app_map_mob_fight')]
    #[IsGranted('ROLE_USER')]
    public function startMobFight(Request $request, MapObject $mob, PlayerCombatManager $combatEngine): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $combatEngine->attack($player, $mob->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}