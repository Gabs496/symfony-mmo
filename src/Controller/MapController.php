<?php

namespace App\Controller;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapSpawnedMob;
use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Combat\Activity\CombatActivity;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Map\Engine\MapEngine;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Game\MapSpawnedResourceRepository;
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
    public function startActivity(NotificationEngine $notificationEngine, MapSpawnedResource $spawnedResource, ActivityEngine $gameActivity, MapSpawnedResourceRepository $mapSpawnedResourceRepository, Request $request): Response
    {
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        //TODO: check if player is on the same map as the resource
        if ($spawnedResource->isEmpty()) {
            $mapSpawnedResourceRepository->remove($spawnedResource);
            $notificationEngine->danger($player->getId(), 'Resource is empty');
            return $this->redirectToRoute('app_map');
        }

        $gameActivity->run($player, new ResourceGatheringActivity($spawnedResource));

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('map/MapAvailableActivity.stream.html.twig', 'remove', [
                'entity' => $spawnedResource,
                'id' => $spawnedResource->getId(),
            ]);
        }
        return $this->redirectToRoute('app_map');
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/craft/{id}', name: 'app_map_craft')]
    public function craftRecipe(ActivityEngine $gameActivity, GameObjectEngine $gameObjectEngine, string $id, Request $request): Response
    {
        /** @var AbstractRecipe $recipe */
        $recipe = $gameObjectEngine->get($id);
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