<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for space condition types
 * 
 * This enum represents the different interior conditions
 * or finishing levels of spaces in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum BuildingConditionEnum: int implements LabelInterface
{
    use EnumHelper;

    case SHELL = 1;
    case CONDITIONED = 2;
    case FURNISHED = 3;

    /**
     * Get the human-readable label for the condition
     * 
     * @return string The label for this condition
     */
    public function label(): string
    {
        return match ($this) {
            self::SHELL => 'Obra gris',
            self::CONDITIONED => 'Acondicionado',
            self::FURNISHED => 'Amueblado',
        };
    }
}

