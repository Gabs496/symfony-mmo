<?php

namespace App\Controller;

use App\Entity\Data\PlayerCharacter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route("/player-character")]
class PlayerCharacterController extends AbstractController
{
    #[Route("/stats", name: "playerCharacter_stats")]
    public function stats(UserInterface $user): Response
    {
        /** @var PlayerCharacter $user */
        return $this->render('player_character/stats.html.twig', [
            'playerCharacter' => $user,
        ]);
    }
}