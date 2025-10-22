<?php

namespace Spot2HQ\SpotValidation\Enums\Spot;

use Spot2HQ\SpotValidation\Enums\EnumHelper;
use Spot2HQ\SpotValidation\Enums\LabelInterface;

/**
 * Enum for roofing types
 * 
 * This enum represents the different types of roofing materials
 * and systems used in buildings.
 * 
 * @package Spot2HQ\SpotValidation\Enums\Spot
 */
enum RoofingTypeEnum: int implements LabelInterface
{
    use EnumHelper;

    case TBD = 1;
    case GALVALOK_II = 2;
    case KR_18 = 3;
    case SHEET = 4;
    case GALVANIZED_SHEET = 5;
    case PINTRO_SHEET = 6;
    case LOSACERO = 7;
    case O_30 = 8;
    case O_100 = 9;
    case R_72 = 10;
    case R_101 = 11;
    case RD_91_5 = 13;
    case RN_100_35 = 14;
    case SSR24 = 15;

    /**
     * Get the human-readable label for the roofing type
     * 
     * @return string The label for this roofing type
     */
    public function label(): string
    {
        return match ($this) {
            self::TBD => 'TBD',
            self::GALVALOK_II => 'Galvalok II',
            self::KR_18 => 'KR-18',
            self::SHEET => 'Lámina',
            self::GALVANIZED_SHEET => 'Lámina galvanizada',
            self::PINTRO_SHEET => 'Lámina pintro',
            self::LOSACERO => 'Losacero',
            self::O_30 => 'O-30',
            self::O_100 => 'O-100',
            self::R_72 => 'R-72',
            self::R_101 => 'R-101',
            self::RD_91_5 => 'RD-91.5',
            self::RN_100_35 => 'RN-100/35',
            self::SSR24 => 'SSR24',
        };
    }
}

