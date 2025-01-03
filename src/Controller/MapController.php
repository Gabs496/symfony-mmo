<?php

namespace App\Controller;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameEngine\Crafting\RecipeCollection;
use App\GameEngine\Map\MapEngine;
use App\GameObject\Activity\RecipeCraftingActivity;
use App\GameObject\Activity\ResourceGatheringActivity;
use App\GameEngine\Activity\ActivityEngine;
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
        ]);
    }

    #[Route('/activity/start/{id}', name: 'app_map_activity_start')]
    public function startActivity(MapAvailableActivity $availableActivity, ActivityEngine $gameActivity, MapAvailableActivityRepository $repository, Request $request): Response
    {
        //TODO: controllare se il giocatore è nella mappa giusta
        if ($availableActivity->isEmpty()) {
            $repository->remove($availableActivity);
            $this->addFlash('danger', 'Activity is not available');
            return $this->redirectToRoute('app_map');
        }

        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $gameActivity->execute($user, $availableActivity, ResourceGatheringActivity::class);

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

    #[Route('/craft//{id}', name: 'app_map_craft')]
    public function craftRecipe(ActivityEngine $gameActivity, RecipeCollection $recipeCollection, string $id, Request $request): Response
    {
        $recipe = $recipeCollection->get($id);
        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $gameActivity->execute($user, $recipe, RecipeCraftingActivity::class);

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}