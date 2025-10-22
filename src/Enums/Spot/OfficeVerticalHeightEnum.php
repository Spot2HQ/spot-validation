<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for office building vertical height classifications
 * 
 * This enum represents the different height classifications
 * for office buildings based on number of floors.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum OfficeVerticalHeightEnum: int implements LabelInterface
{
    use EnumHelper;

    case LESS_THAN_6_FLOORS = 1;
    case BETWEEN_6_AND_10_FLOORS = 2;
    case MORE_THAN_10_FLOORS = 3;

    /**
     * Get the human-readable label for the office vertical height
     * 
     * @return string The label for this office vertical height
     */
    public function label(): string
    {
        return match ($this) {
            self::LESS_THAN_6_FLOORS => 'Menor de 6 pisos',
            self::BETWEEN_6_AND_10_FLOORS => 'Menor de 10 y mayor a 6 pisos',
            self::MORE_THAN_10_FLOORS => 'Mayor de 10 pisos',
        };
    }
}

