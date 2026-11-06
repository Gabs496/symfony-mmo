<?php

namespace App\Security;

use App\Entity\Data\Player;
use App\Stream\ItemBagStream;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Mercure\HubInterface;

/**
 * Gives the logged player the "mercureAuthorization" cookie needed to receive the PRIVATE Mercure updates
 * meant for them (the browser EventSource cannot send an Authorization header, only cookies).
 *
 * The cookie is a single JWT per hub, so it always lists ALL the private topics of the player: setting it
 * per-component would make every request overwrite the topics granted by the previous one.
 *
 * The cookie is built here instead of with Authorization::createCookie() because the hub is served on the same
 * origin as the app (relative public URL, see environment/nginx.conf): createCookie() would mark it "Secure"
 * even on plain HTTP, and the browser would drop it.
 */
#[AsEventListener]
readonly class MercureSubscriptionListener
{
    private const string COOKIE_NAME = 'mercureAuthorization'; // name expected by the Mercure hub
    private const int LIFETIME_SECONDS = 3600 * 12;

    public function __construct(
        private Security     $security,
        private HubInterface $hub,
    )
    {
    }

    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || !($player = $this->security->getUser()) instanceof Player) {
            return;
        }

        $expiresAt = new DateTimeImmutable('+' . self::LIFETIME_SECONDS . ' seconds');
        $token = $this->hub->getFactory()->create(self::getPrivateTopics($player), [], ['exp' => $expiresAt]);
        $request = $event->getRequest();

        $event->getResponse()->headers->setCookie(Cookie::create(
            self::COOKIE_NAME,
            $token,
            $expiresAt,
            parse_url($this->hub->getPublicUrl(), PHP_URL_PATH) ?: '/',
            null,
            $request->isSecure(),
            true,
            false,
            Cookie::SAMESITE_STRICT,
        ));
    }

    /** @return string[] */
    public static function getPrivateTopics(Player $player): array
    {
        return [
            'player_gui_' . $player->getId(),
            ItemBagStream::getTopic($player->getGameObject()->getId()),
        ];
    }
}
