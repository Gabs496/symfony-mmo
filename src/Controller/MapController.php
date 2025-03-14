<?php

namespace App\Controller;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Combat\Activity\CombatActivity;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Crafting\Engine\RecipeCollection;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Map\Engine\MapEngine;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\MapAvailableActivityRepository;
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
    public function home(MapEngine $mapEngine, RecipeCollection $recipeCollection): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'player' => $user,
            'mapAvailableActivities' => $mapEngine->getAvailableActivities($user->getMap()),
            'recipes' => $recipeCollection->all(),
            'spawnedMobs' => $mapEngine->getSpawnedMobs($user->getMap()),
        ]);
    }

    #[Route('/activity/start/{id}', name: 'app_map_activity_start')]
    public function startActivity(NotificationEngine $notificationEngine, MapAvailableActivity $availableActivity, ActivityEngine $gameActivity, MapAvailableActivityRepository $repository, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        //TODO: controllare se il giocatore è nella mappa giusta
        if ($availableActivity->isEmpty()) {
            $repository->remove($availableActivity);
            $notificationEngine->danger($player->getId(), 'Activity is not available');
            return $this->redirectToRoute('app_map');
        }

        $gameActivity->run($player, new ResourceGatheringActivity($availableActivity));

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('map/MapAvailableActivity.stream.html.twig', 'remove', [
                'entity' => $availableActivity,
                'id' => $availableActivity->getId(),
            ]);
        }
        return $this->redirectToRoute('app_map');
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/craft/{id}', name: 'app_map_craft')]
    public function craftRecipe(ActivityEngine $gameActivity, RecipeCollection $recipeCollection, string $id, Request $request): Response
    {
        $recipe = $recipeCollection->get($id);
        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $gameActivity->run($user, new RecipeCraftingActivity($recipe));

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/mob-fight/{id}', name: 'app_map_mob_fight')]
    public function startMobFight(ActivityEngine $gameActivity, Request $request, MapSpawnedMob $mapSpawnedMob): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $gameActivity->run($player, new CombatActivity($player, $mapSpawnedMob));

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}