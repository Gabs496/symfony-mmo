<?php

namespace App\Controller;

use App\Entity\Data\Mastery;
use App\Entity\Data\PlayerCharacter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/home', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function home(EntityManagerInterface $em): Response
    {
        /** @var PlayerCharacter $user */
        $user = $this->getUser();

//        if ($user->getMasteries() === null) {
//            $user->setMasteries(new Mastery());
//            $em->flush();
//            $em->refresh($user);
//        }
        return $this->render('main/home.html.twig', [
            'map' => $user->getPosition(),
        ]);
    }
}
