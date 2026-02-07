<?php

namespace App\Controller;

use App\Engine\Item\ItemActionEngine;
use App\Entity\Data\Player;
use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Equipment\Component\EquipmentComponent;
use App\GameElement\Equipment\EquipmentEngine;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Position\PositionEngine;
use LogicException;
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
        private readonly EquipmentEngine $equipmentEngine,
        private readonly PositionEngine $positionEngine,
    )
    {
    }

    #[Route('/content/{id}', name: 'app_itemBag_content')]
    public function content(ItemBagComponent $itemBag): Response
    {
        return $this->render('item_bag/content.html.twig', [
            'bag' => $itemBag,
        ]);
    }

    #[Route('/item/drop/{id}', name: 'app_item_drop')]
    public function drop(GameObject $item, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var Player $player */
        $player = $this->getUser();
        $this->positionEngine->move($item, $player->getMap(), 'field');

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/eat/{id}', name: 'app_item_eat')]
    public function eat(GameObject $item, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var Player $player */
        $player = $this->getUser();
        $this->itemActionEngine->eat($player, $item);

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/equip/{id}', name: 'app_item_equip')]
    public function equip(GameObject $item, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var Player $player */
        $player = $this->getUser();
        $equipmentComponent = $item->getComponent(EquipmentComponent::class);
        if (!$equipmentComponent) {
            throw new LogicException('Invalid item type for equip action');
        }
        $this->equipmentEngine->equip($item, $player->getGameObject(), $equipmentComponent->getTargetSlot());

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }

    #[Route('/item/unequip/{slot}', name: 'app_item_unequip')]
    public function unequip(string $slot, Request $request): Response
    {
        //TODO: check permissions to execute action on object
        /** @var Player $player */
        $player = $this->getUser();
        $this->equipmentEngine->unequip($player->getGameObject(), $slot);

        if ($request->headers->get('Turbo-Frame')) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return new Response();
        }
        return $this->redirectToRoute('app_map');
    }
}