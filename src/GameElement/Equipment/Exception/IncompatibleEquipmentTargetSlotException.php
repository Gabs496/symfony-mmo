<?php

namespace App\GameElement\Equipment\Exception;

use App\GameElement\Equipment\Component\EquipmentSetComponent;
use Exception;
use Throwable;

class IncompatibleEquipmentTargetSlotException extends Exception
{
    public function __construct(
        private readonly string    $targetSlot,
        private readonly array $allowedSlots,
        string $message = "", int $code = 0, ?Throwable $previous = null
    )
    {
        parent::__construct(
            $message ?: sprintf("Incompatible equipment slot (%s) with allowed ones (%s)", $this->targetSlot, implode(", ", $this->allowedSlots)),
            $code,
            $previous
        );
    }

    public function getTargetSlot(): string
    {
        return $this->targetSlot;
    }

    public function getAllowedSlots(): array
    {
        return $this->allowedSlots;
    }
}