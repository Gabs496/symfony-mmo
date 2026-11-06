<?php

namespace App\Stream;

use App\GameElement\Item\Component\ItemBagComponent;
use PennyPHP\Core\GameObjectInterface;

/**
 * PRIVATE Turbo Stream for the item bag of a player (only its owner may receive it).
 * Action is one of the blocks of streams/item_bag.stream.html.twig: "append", "update", "remove", "fullness".
 * $bagId is the id of the GameObject owning the bag (the same value stored in ItemInBagSlotComponent::bagId).
 */
readonly class ItemBagStream implements BroadcastStreamInterface
{
    public const string APPEND = 'append';
    public const string UPDATE = 'update';
    public const string REMOVE = 'remove';
    public const string FULLNESS = 'fullness';

    /** @param GameObjectInterface|ItemBagComponent $subject the item for append/update/remove, the bag for fullness */
    public function __construct(
        private string                               $bagId,
        private GameObjectInterface|ItemBagComponent $subject,
        private string                               $action,
        private ?float                               $fullness = null,
    )
    {
    }

    public static function getTopic(string $bagId): string
    {
        return 'item_bag_' . $bagId;
    }

    public function getTopics(): array
    {
        return [self::getTopic($this->bagId)];
    }

    public function getTemplate(): string
    {
        return 'streams/item_bag.stream.html.twig';
    }

    public function getOptions(): array
    {
        return [
            'bagId' => $this->bagId,
            'item' => $this->subject,
            'id' => $this->subject instanceof GameObjectInterface ? $this->subject->getId() : null,
            'fullness' => $this->fullness,
        ];
    }

    public function getObject(): ?object
    {
        return $this->subject;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }
}
