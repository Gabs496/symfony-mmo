<?php

namespace App\Controller;

use App\Entity\Data\Player;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Crafting\Engine\CraftingEngine;
use App\GameElement\Crafting\Exception\IngredientNotAvailableException;
use App\GameElement\Gathering\Engine\GatheringEngine;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\GameObject\PlayerCharacter\BasePlayer;
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
    public function home(CraftingEngine $craftingEngine): Response
    {
        /** @var Player $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'player' => $user,
            'recipes' => $craftingEngine->getRecipes(),
        ]);
    }

    #[Route('/map/field', name: 'app_map_field')]
    #[IsGranted('ROLE_USER')]
    public function field(): Response
    {
        /** @var Player $user */
        $user = $this->getUser();

        return $this->render('map/field.html.twig', [
            'mapObjects' => $user->getMap()->getFields(),
        ]);
    }

    #[Route('/resource_gather/{id}', name: 'app_map_resource_gather')]
    #[IsGranted('ROLE_USER')]
    public function startGathering(MapObject $resource, GatheringEngine $gatheringEngine, Request $request): Response
    {
        /** @var Player $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource
        $gatheringEngine->startGathering($player->getGameObject(), $resource->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/craft/{id}', name: 'app_map_craft')]
    #[IsGranted('ROLE_USER')]
    public function startCraftingRecipe(CraftingEngine $craftingEngine, BasePlayer $basePlayer, string $id, Request $request): Response
    {
        /** @var Player $user */
        $user = $this->getUser();

        try {
            $craftingEngine->startCrafting($user->getGameObject(), $id, $basePlayer);
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
    public function startMobFight(Request $request, MapObject $mob, CombatEngine $combatEngine): Response
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