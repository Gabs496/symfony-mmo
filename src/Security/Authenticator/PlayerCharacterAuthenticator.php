<?php

namespace App\Security\Authenticator;

use App\Entity\Security\User;
use App\Repository\PlayerCharacterRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class PlayerCharacterAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly PlayerCharacterRepository $repository,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_character_login'
            && $request->get('character_id') !== null
            && $request->get('csrf_token') !== null
        ;
    }

    public function authenticate(Request $request): Passport
    {
        $characterId = $request->get('character_id');
        $csrfToken = $request->get('csrf_token');

        if (!$this->csrfTokenManager->isTokenValid( new CsrfToken('character_login', $csrfToken))) {
            throw new AuthenticationException('Invalid CSRF token');
        }

        return new SelfValidatingPassport(new UserBadge($characterId, function (string $characterId) {
            return $this->repository->find($characterId);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_character_login'));    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        if ($request->getUser() instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('app_character_login'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}