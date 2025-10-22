<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for space between columns
 * 
 * This enum represents the different ranges of spacing
 * between structural columns in buildings.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum SpaceBetweenColumnsEnum: int implements LabelInterface
{
    use EnumHelper;

    case LESS_THAN_8_METERS = 1;
    case BETWEEN_8_AND_12_METERS = 2;
    case BETWEEN_12_AND_16_METERS = 3;

    /**
     * Get the human-readable label for the space between columns
     * 
     * @return string The label for this space between columns
     */
    public function label(): string
    {
        return match ($this) {
            self::LESS_THAN_8_METERS => 'Menos de 8 metros',
            self::BETWEEN_8_AND_12_METERS => '8 a 12 metros',
            self::BETWEEN_12_AND_16_METERS => '12 a 16 metros',
        };
    }
}

