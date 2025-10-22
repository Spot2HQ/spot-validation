<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for fire protection systems
 * 
 * This enum represents the different types of fire protection
 * systems available in buildings.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum FireProtectionSystemEnum: int implements LabelInterface
{
    use EnumHelper;

    case SPRINKLERS = 1;
    case HYDRANTS = 2;
    case FIRE_EXTINGUISHERS = 3;

    /**
     * Get the human-readable label for the fire protection system
     * 
     * @return string The label for this fire protection system
     */
    public function label(): string
    {
        return match ($this) {
            self::SPRINKLERS => 'Rociadores',
            self::HYDRANTS => 'Hidrantes',
            self::FIRE_EXTINGUISHERS => 'Extintores',
        };
    }
}

