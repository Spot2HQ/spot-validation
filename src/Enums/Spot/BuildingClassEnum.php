<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for building classification
 * 
 * This enum represents the quality classification of buildings
 * in the system (A+, A, B, C).
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum BuildingClassEnum: int implements LabelInterface
{
    use EnumHelper;

    case A_PLUS = 1;
    case A = 2;
    case B = 3;
    case C = 4;

    /**
     * Get the human-readable label for the class
     * 
     * @return string The label for this class
     */
    public function label(): string
    {
        return match ($this) {
            self::A_PLUS => 'A+',
            self::A => 'A',
            self::B => 'B',
            self::C => 'C',
        };
    }
}

