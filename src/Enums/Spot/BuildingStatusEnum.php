<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for building construction status
 * 
 * This enum represents the current status of construction
 * for buildings in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum BuildingStatusEnum: int implements LabelInterface
{
    use EnumHelper;

    case COMPLETED = 1;
    case UNDER_CONSTRUCTION = 2;
    case PROJECT = 3;

    /**
     * Get the human-readable label for the building status
     * 
     * @return string The label for this building status
     */
    public function label(): string
    {
        return match ($this) {
            self::COMPLETED => 'Terminado',
            self::UNDER_CONSTRUCTION => 'En construcción',
            self::PROJECT => 'Proyecto',
        };
    }
}

