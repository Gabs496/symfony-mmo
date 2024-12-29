<?php

namespace App\Controller;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameRule\GameActivity;
use App\GameRule\Map;
use App\Repository\Data\MapAvailableActivityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/map')]
class MapController extends AbstractController
{
    public function __construct(private readonly MapAvailableActivityRepository $mapAvailableActivityRepository)
    {
    }

    #[Route('/', name: 'app_map')]
    #[IsGranted('ROLE_USER')]
    public function home(Map $map): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'playerPosition' => $user->getPosition(),
            'mapAvailableActivities' => $map->getAvailableActivities($user->getPosition()),
        ]);
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    #[Route('/activity/start/{id}', name: 'app_map_activity_start')]
    public function startActivity(MapAvailableActivity $availableActivity, GameActivity $gameActivity, MapAvailableActivityRepository $repository, Request $request): Response
    {
        //TODO: controllare se il giocatore è nella mappa giusta
        if ($availableActivity->isEmpty()) {
            $repository->remove($availableActivity);
            $this->addFlash('danger', 'Activity is not available');
            return $this->redirectToRoute('app_map');
        }

        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $activity = $gameActivity->createFromMapAvailableActivity($user, $availableActivity);
        $activity->addMapAvailableActivity($availableActivity);
        $repository->save($availableActivity);

        $gameActivity->startPlayerActivity($user, $activity);
        $this->mapAvailableActivityRepository->remove($availableActivity);

        $this->addFlash('success', 'Activity finished');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('broadcast/Data/MapAvailableActivity.stream.html.twig', 'remove', [
                'entity' => $availableActivity,
                'id' => $availableActivity->getId(),
            ]);
        }
        return $this->redirectToRoute('app_map');
    }
}