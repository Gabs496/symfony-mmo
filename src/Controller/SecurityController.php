<?php

namespace App\Controller;

use App\Entity\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/user/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $this->redirectToRoute('app_choose_character');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/user/choose-character', name: 'app_choose_character')]
    #[IsGranted('ROLE_USER')]
    public function chooseCharacter(CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $characters = $user->getPlayerCharacters();

        if ($characters->count() === 1) {
            return $this->redirectToRoute('app_character_login', [
                'character_id' => $characters->first()->getId(),
                'csrf_token' => $csrfTokenManager->getToken('character_login')->getValue()
            ]);
        }

        // stampa la lista dei personaggi
    }

    #[Route(path: '/user/logout', name: 'app_logout')]
    #[IsGranted('ROLE_USER')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/character-login', name: 'app_character_login')]
    public function characterLogin()
    {}
}
