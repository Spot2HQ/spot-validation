<?php

namespace Spot2HQ\SpotValidation\Enums\Spot\Price;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for area calculation types
 * 
 * This enum represents the different methods for calculating
 * or expressing area-related values in the system.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot\Price
 */
enum PriceAreaTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case TOTAL = 1;
    case PER_SQUARE_METER = 2;
    case PERCENTAGE = 3;

    /**
     * Get the human-readable label for the area type
     * 
     * @return string The label for this area type
     */
    public function label(): string
    {
        return match ($this) {
            self::TOTAL => 'total',
            self::PER_SQUARE_METER => 'por metro cuadrado',
            self::PERCENTAGE => 'por porcentaje',
        };
    }
}

