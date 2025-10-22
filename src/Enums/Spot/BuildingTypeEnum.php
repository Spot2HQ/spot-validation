<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for building construction types
 * 
 * This enum represents the different construction materials
 * and methods used for buildings in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum BuildingTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case STEEL_AND_CONCRETE = 1;
    case BLOCK_AND_SHEET = 2;
    case STEEL_BLOCK_AND_SHEET = 3;
    case SHEET = 4;

    /**
     * Get the human-readable label for the building type
     * 
     * @return string The label for this building type
     */
    public function label(): string
    {
        return match ($this) {
            self::STEEL_AND_CONCRETE => 'Acero y concreto',
            self::BLOCK_AND_SHEET => 'Block y Lámina',
            self::STEEL_BLOCK_AND_SHEET => 'Acero con block y lámina',
            self::SHEET => 'Lámina',
        };
    }
}

