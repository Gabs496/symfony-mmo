<?php

namespace App\Controller;

use App\Engine\Player\PlayerItemEngine;
use App\Entity\Data\Player;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Crafting\Engine\CraftingEngine;
use App\GameElement\Crafting\Exception\IngredientNotAvailableException;
use App\GameElement\Gathering\Engine\GatheringEngine;
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
        private readonly InMapRepository  $inMapRepository,
        private readonly CraftingEngine   $craftingEngine,
        private readonly PlayerItemEngine $playerItemEngine,
        private readonly GatheringEngine $gatheringEngine,
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
            'recipes' => $this->craftingEngine->getRecipes(),
        ]);
    }

    #[Route('/map/field', name: 'app_map_field')]
    #[IsGranted('ROLE_USER')]
    public function field(): Response
    {
        /** @var Player $user */
        $user = $this->getUser();
        $mapId = $user->getMap();
        $objects = array_filter($this->inMapRepository->findInMap($mapId, 'field'), function (InMapComponent $inMapComponent) use ($user) {
            return $inMapComponent->getGameObject()->getId() !== $user->getGameObject()->getId();
        });

        return $this->render('map/field.frame.html.twig', [
            'inMapComponents' => $objects,
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

        try {
            $this->craftingEngine->startCrafting($user->getGameObject(), $id, $this->playerItemEngine);
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