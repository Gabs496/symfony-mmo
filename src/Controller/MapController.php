<?php

namespace App\Controller;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameRule\GameActivity;
use App\Repository\Data\MapAvailableActivityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/map')]
class MapController extends AbstractController
{
    #[Route('/', name: 'app_map')]
    #[IsGranted('ROLE_USER')]
    public function home(): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

        return $this->render('map/home.html.twig', [
            'map' => $user->getPosition(),
        ]);
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    #[Route('/activity/start/{id}', name: 'app_map_activity_start')]
    public function startActivity(MapAvailableActivity $availableActivity, GameActivity $gameActivity, MapAvailableActivityRepository $repository): RedirectResponse
    {
        if ($availableActivity->isEmpty()) {
            $repository->remove($availableActivity);
            $this->addFlash('danger', 'Activity is not available');
            return $this->redirectToRoute('app_map');
        }

        /** @var PlayerCharacter $user */
        $user = $this->getUser();
        $activity = $gameActivity->createFromMapAvailableActivity($user, $availableActivity);
        $gameActivity->startPlayerActivity($user, $activity);

        $this->addFlash('success', 'Activity started');

        return $this->redirectToRoute('app_map');
    }
}