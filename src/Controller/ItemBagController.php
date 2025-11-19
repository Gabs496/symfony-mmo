<?php

namespace App\Controller;

use App\Engine\Item\ItemActionEngine;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Item\ItemBag;
use App\Entity\Item\ItemObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/item-bag')]
class ItemBagController extends AbstractController
{
    public function __construct(
        private readonly ItemActionEngine $itemActionEngine,
    )
    {
    }

    #[Route('/content/{id}', name: 'app_itemBag_content')]
    public function content(ItemBag $itemBag): Response
    {
        return $this->render('item_bag/content.html.twig', [
            'bag' => $itemBag,
        ]);
    }

    #[Route('/item/drop/{id}', name: 'app_item_drop')]
    public function drop(ItemObject $itemObject, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $this->itemActionEngine->drop($player, $itemObject->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/eat/{id}', name: 'app_item_eat')]
    public function eat(ItemObject $itemObject, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $this->itemActionEngine->eat($player, $itemObject->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/equip/{id}', name: 'app_item_equip')]
    public function equip(ItemObject $itemObject, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $this->itemActionEngine->equip($player, $itemObject->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/unequip/{id}', name: 'app_item_unequip')]
    public function unequip(ItemObject $itemObject, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var PlayerCharacter $player */
        $player = $this->getUser();
        $this->itemActionEngine->unequip($player, $itemObject->getGameObject());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}